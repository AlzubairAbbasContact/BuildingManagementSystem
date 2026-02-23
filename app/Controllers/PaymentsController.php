<?php 

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;

class PaymentsController extends Controller {
    private $paymentModel;
    private $tenantModel;

    public function __construct() {
        Session::requireLogin();
        $this->paymentModel = $this->model('Payment');
        $this->tenantModel = $this->model('Tenant');
    }

    public function index() {
        $payments = $this->paymentModel->getAllPayments();
        // Attach dynamic remaining (rent - sum(active payments)) and tenant status to each payment row
        foreach ($payments as $p) {
            $tenant = $this->tenantModel->getTenantById($p->tenant_id);
            if ($tenant) {
                $paid = $this->paymentModel->sumActivePaymentsByTenant($tenant->id);
                $p->remaining = $tenant->rent_amount - $paid;
                $p->tenant_status = isset($tenant->status) ? $tenant->status : 'active';
                $p->tenant_rent = $tenant->rent_amount;
            } else {
                $p->remaining = 0;
                $p->tenant_status = 'unknown';
                $p->tenant_rent = 0;
            }
        }
        $data = [
            'active' => 'payments',
            'payments' => $payments
        ];
        $this->view('payments/index', $data);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
             \App\Core\Csrf::verify();
             $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
             
             $data = [
                 'active' => 'payments',
                 'tenant_id' => trim($_POST['tenant_id']),
                 'amount_paid' => trim($_POST['amount_paid']),
                 'payment_date' => trim($_POST['payment_date']),
                 'notes' => trim($_POST['notes']),
                 'amount_paid_err' => ''
             ];

             if (empty($data['amount_paid'])) {
                 $data['amount_paid_err'] = 'المبلغ مطلوب';
             } elseif (!Validator::validatePositiveNumber($data['amount_paid'])) {
                 $data['amount_paid_err'] = 'المبلغ يجب أن يكون اكثر من 0 ريال ';
             }

             if (empty($data['amount_paid_err'])) {
                 // Calculate Remaining
                 // 1. Get Tenant Rent
                 $tenant = $this->tenantModel->getTenantById($data['tenant_id']);
                 if ($tenant) {
                     if (isset($tenant->status) && $tenant->status !== 'active') {
                         $data['amount_paid_err'] = 'لا يمكن تسجيل دفعة لمستأجر غير نشط.';
                     } else {
                         $rentAmount = $tenant->rent_amount;
                         // sum of already active payments
                         $alreadyPaid = $this->paymentModel->sumActivePaymentsByTenant($tenant->id);
                         $remaining = $rentAmount - $alreadyPaid;

                         // Validate not overpaying
                         if ((float)$data['amount_paid'] > (float)$remaining) {
                             $data['amount_paid_err'] = 'المبلغ أكبر من المتبقي (' . number_format($remaining,2) . ')';
                         } else {
                             // Prepare payment data (do not store amount_remaining)
                             $pm = [
                                 'tenant_id' => $tenant->id,
                                 'amount_paid' => $data['amount_paid'],
                                 'payment_date' => $data['payment_date'],
                                 'notes' => $data['notes'],
                                 'status' => 'active'
                             ];

                             if ($this->paymentModel->addPayment($pm)) {
                                 $newRemaining = $remaining - $data['amount_paid'];
                                 Session::flash('payment_msg', 'تم تسجيل الدفعة بنجاح. المبلغ المتبقي: ' . number_format($newRemaining,2));
                                 $this->redirect('payments');
                             } else {
                                 // Show system error in-site
                                 Session::flash('payment_msg', 'خطأ في النظام أثناء حفظ الدفعة');
                                 $this->redirect('payments');
                             }
                         }
                     }
                 } else {
                     $data['amount_paid_err'] = 'المستأجر غير موجود';
                     // fall through to render form with error below
                 }
                 // If any validation error occurred during processing, render the form with errors
                 if (!empty($data['amount_paid_err'])) {
                     $tenants = $this->tenantModel->getActiveTenants();
                     $data['tenants'] = $tenants;
                     $this->view('payments/add', $data);
                     return;
                 }
             } else {
                 // Show only active tenants in selector
                 $tenants = $this->tenantModel->getActiveTenants();
                 $data['tenants'] = $tenants;
                 $this->view('payments/add', $data);
             }

        } else {
            $tenants = $this->tenantModel->getActiveTenants();
            $data = [
                'active' => 'payments',
                'amount_paid' => '',
                'payment_date' => date('Y-m-d'),
                'notes' => '',
                'tenants' => $tenants,
                'amount_paid_err' => ''
            ];
            $this->view('payments/add', $data);
        }
    }

    public function delete($id) {
        // Soft-cancel payment instead of deleting
        if ($this->paymentModel->cancelPayment($id, null)) {
            Session::flash('payment_msg', 'تم إلغاء الدفعة بنجاح');
            $this->redirect('payments');
        } else {
            die('خطأ في الإلغاء');
        }
    }

    // Show cancel form (GET) and handle cancel action (POST)
    public function cancel($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            \App\Core\Csrf::verify();
            $reason = isset($_POST['reason']) ? trim($_POST['reason']) : null;
            if ($this->paymentModel->cancelPayment($id, $reason)) {
                Session::flash('payment_msg', 'تم إلغاء الدفعة بنجاح');
                $this->redirect('payments');
            } else {
                die('خطأ في الإلغاء');
            }
        } else {
            $payment = $this->paymentModel->getPaymentById($id);
            if (!$payment) {
                die('الدفعة غير موجودة');
            }
            // Attach tenant name if possible
            $tenant = $this->tenantModel->getTenantById($payment->tenant_id);
            $data = [
                'active' => 'payments',
                'payment' => $payment,
                'reason' => ''
            ];
            if ($tenant) {
                $data['payment']->tenant_name = $tenant->name;
            }
            $this->view('payments/cancel', $data);
        }
    }
}
