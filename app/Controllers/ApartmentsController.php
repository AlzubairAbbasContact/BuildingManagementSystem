<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;

class ApartmentsController extends Controller {
    private $apartmentModel;

    public function __construct() {
        Session::requireLogin();
        $this->apartmentModel = $this->model('Apartment');
    }

    public function index() {
        $apartments = $this->apartmentModel->getAllApartments();
        $data = [
            'active' => 'apartments',
            'apartments' => $apartments
        ];
        $this->view('apartments/index', $data);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
             \App\Core\Csrf::verify();
             $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
             
             $data = [
                 'active' => 'apartments',
                 'apartment_number' => trim($_POST['apartment_number']),
                 'floor' => trim($_POST['floor']),
                 'status' => trim($_POST['status']),
                 'notes' => trim($_POST['notes']),
                 'apartment_number_err' => '',
                 'floor_err' => ''
             ];

             // Validate Number
             if (empty($data['apartment_number'])) {
                 $data['apartment_number_err'] = 'أدخل رقم الشقة';
             }
             
             if (empty($data['floor'])) {
                 $data['floor_err'] = 'أدخل الدور';
             } elseif (!Validator::validatePositiveNumber($data['floor'])) {
                 $data['floor_err'] = 'رقم الدور يجب أن يكون رقماً موجباً';
             }

             // Check Duplicate
             if (empty($data['apartment_number_err']) && empty($data['floor_err'])) {
                 if ($this->apartmentModel->checkDuplicate($data['apartment_number'], $data['floor'])) {
                     $data['apartment_number_err'] = 'هذه الشقة موجودة بالفعل في هذا الدور';
                 }
             }

             if (empty($data['apartment_number_err']) && empty($data['floor_err'])) {
                 if ($this->apartmentModel->addApartment($data)) {
                     Session::flash('apartment_msg', 'تم إضافة الشقة بنجاح');
                     $this->redirect('apartments');
                 } else {
                     die('خطأ في النظام');
                 }
             } else {
                 $this->view('apartments/add', $data);
             }

        } else {
            $data = [
                'active' => 'apartments',
                'apartment_number' => '',
                'floor' => '',
                'status' => 'vacant',
                'notes' => '',
                'apartment_number_err' => '',
                'floor_err' => ''
            ];
            $this->view('apartments/add', $data);
        }
    }

    public function delete($id) {
        if ($this->apartmentModel->deleteApartment($id)) {
            Session::flash('apartment_msg', 'تم حذف الشقة بنجاح');
            $this->redirect('apartments');
        } else {
            die('خطأ في الحذف');
        }
    }
}
