<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

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
                 'amount_remaining' => trim($_POST['amount_remaining']),
                 'payment_date' => trim($_POST['payment_date']),
                 'notes' => trim($_POST['notes']),
                 'amount_paid_err' => ''
             ];

             if (empty($data['amount_paid'])) {
                 $data['amount_paid_err'] = 'المبلغ مطلوب';
             } elseif (!\App\Core\Validator::validatePositiveNumber($data['amount_paid'])) {
                 $data['amount_paid_err'] = 'المبلغ يجب أن يكون رقماً موجباً';
             }

             if (empty($data['amount_paid_err'])) {
                 // Calculate Remaining
                 // 1. Get Tenant Rent
                 $tenant = $this->tenantModel->getTenantById($data['tenant_id']);
                 if ($tenant) {
                     $rentAmount = $tenant->rent_amount;
                     $amountPaid = $data['amount_paid'];
                     
                     // Logic: Remaining = Rent - Paid (Simple logic as requested)
                     $amountRemaining = $rentAmount - $amountPaid;
                     $data['amount_remaining'] = $amountRemaining;

                     if ($this->paymentModel->addPayment($data)) {
                         Session::flash('payment_msg', 'تم تسجيل الدفعة بنجاح. المبلغ المتبقي: ' . number_format($amountRemaining));
                         $this->redirect('payments');
                     } else {
                         die('خطأ في النظام');
                     }
                 } else {
                      die('المستأجر غير موجود');
                 }
             } else {
                 $tenants = $this->tenantModel->getAllTenants();
                 $data['tenants'] = $tenants;
                 $this->view('payments/add', $data);
             }

        } else {
            $tenants = $this->tenantModel->getAllTenants();
            $data = [
                'active' => 'payments',
                'amount_paid' => '',
                'amount_remaining' => '', // Still keeping key to avoid view error if used, but empty
                'payment_date' => date('Y-m-d'),
                'notes' => '',
                'tenants' => $tenants,
                'amount_paid_err' => ''
            ];
            $this->view('payments/add', $data);
        }
    }

    public function delete($id) {
        if ($this->paymentModel->deletePayment($id)) {
            Session::flash('payment_msg', 'تم حذف الدفعة بنجاح');
            $this->redirect('payments');
        } else {
            die('خطأ في الحذف');
        }
    }
}
