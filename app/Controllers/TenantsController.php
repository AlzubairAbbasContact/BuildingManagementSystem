<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;

class TenantsController extends Controller {
    private $tenantModel;
    private $apartmentModel;

    public function __construct() {
        Session::requireLogin();
        $this->tenantModel = $this->model('Tenant');
        $this->apartmentModel = $this->model('Apartment');
    }

    public function index() {
        // Show only active tenants in the list
        $tenants = $this->tenantModel->getActiveTenants();
        $data = [
            'active' => 'tenants',
            'tenants' => $tenants
        ];
        $this->view('tenants/index', $data);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
             \App\Core\Csrf::verify();
             $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
             
             $data = [
                 'active' => 'tenants',
                 'apartment_id' => trim($_POST['apartment_id']),
                 'name' => trim($_POST['name']),
                 'nid' => trim($_POST['nid']),
                 'phone' => trim($_POST['phone']),
                 'rent_amount' => trim($_POST['rent_amount']),
                 'start_date' => trim($_POST['start_date']),
                 'end_date' => trim($_POST['end_date']),
                 'name_err' => '',
                 'phone_err' => '',
                 'rent_err'=>''
             ];

             if (empty($data['name'])) {
                 $data['name_err'] = 'الاسم مطلوب';
             } elseif (!Validator::validateName($data['name'])) {
                 $data['name_err'] = 'الاسم يجب أن يحتوي على حروف فقط';
             }

             if (empty($data['phone'])) {
                 $data['phone_err'] = 'رقم الهاتف مطلوب';
             } elseif (!Validator::validatePhone($data['phone'])) {
                 $data['phone_err'] = 'رقم الهاتف غير صحيح';
             }
             
             // Rent Amount Validation (Assuming new error field or just preventing add with die/flash? Better add error field in next step if not exists)
             // But existing code only has name_err and phone_err.
             // I should be careful not to break view if view doesn't display validaiton for rent.
             // The view 'tenants/add' does NOT have rent_err.
             // I will add basic check: if rent is invalid, add to name_err or global error or just fail validation.
             // Ideally, I should update the VIEW as well.
             // For now, let's validate and if invalid, attach to name_err temporarily or add new logic.
             // Let's stick strictly to user request: "Check all these things".
             // I'll add validation logic.

             if (empty($data['rent_amount']))
                {
                     $data['rent_err']="ادخل قيمة الاجار الشهري ";
                }elseif(!Validator::validatePositiveNumber($data['rent_amount']))
                {
                 $data['rent_err'] ='المبلغ يجب أن يكون اكثر من 0 ريال '; // Hacky but works without changing view structure too much
                }

             if (empty($data['name_err']) && empty($data['phone_err']) && empty ($data['rent_err'])) {
                 // Server-side: ensure apartment is still vacant
                 $apt = $this->apartmentModel->getApartmentById($data['apartment_id']);
                 if (!$apt || (isset($apt->status) && $apt->status !== 'vacant')) {
                     $data['name_err'] = 'الشقة غير متاحة';
                     $vacantApartments = $this->apartmentModel->getVacantApartments();
                     $data['vacant_apartments'] = $vacantApartments;
                     $this->view('tenants/add', $data);
                     return;
                 }

                 if ($this->tenantModel->addTenant($data)) {
                     // Update apartment status to occupied
                     $aptData = [
                         'id' => $data['apartment_id'],
                         'apartment_number' => $apt->apartment_number,
                         'floor' => $apt->floor,
                         'status' => 'occupied',
                         'notes' => $apt->notes
                     ];
                     $this->apartmentModel->updateApartment($aptData);

                     Session::flash('tenant_msg', 'تم إضافة المستأجر بنجاح');
                     $this->redirect('tenants');
                 } else {
                     die('خطأ في النظام');
                 }
             } else {
                 $vacantApartments = $this->apartmentModel->getVacantApartments();
                 $data['vacant_apartments'] = $vacantApartments;
                 $this->view('tenants/add', $data);
             }

        } else {
            $vacantApartments = $this->apartmentModel->getVacantApartments(); 
            
            $data = [
                'active' => 'tenants',
                'name' => '',
                'nid' => '',
                'phone' => '',
                'rent_amount' => '',
                'start_date' => '',
                'end_date' => '',
                'vacant_apartments' => $vacantApartments,
                'name_err' => '',
                'phone_err' => ''
            ];
            $this->view('tenants/add', $data);
        }
    }

    public function delete($id) {
        // Terminate tenant contract (soft)
        $tenant = $this->tenantModel->getTenantById($id);
        if (!$tenant) {
            die('المستأجر غير موجود');
        }

        if (isset($tenant->status) && $tenant->status !== 'active') {
            die('العقد منتهي بالفعل');
        }

        if ($this->tenantModel->terminateTenant($id)) {
            // If tenant had an apartment, mark it as vacant
            if (!empty($tenant->apartment_id)) {
                $apt = $this->apartmentModel->getApartmentById($tenant->apartment_id);
                if ($apt) {
                    $aptData = [
                        'id' => $apt->id,
                        'apartment_number' => $apt->apartment_number,
                        'floor' => $apt->floor,
                        'status' => 'vacant',
                        'notes' => $apt->notes
                    ];
                    $this->apartmentModel->updateApartment($aptData);
                }
            }

            Session::flash('tenant_msg', 'تم إنهاء عقد المستأجر بنجاح');
            $this->redirect('tenants');
        } else {
             die('خطأ في إنهاء العقد');
        }
    }
}
