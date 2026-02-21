<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;

class UsersController extends Controller {
    private $userModel;

    public function __construct() {
        Session::requireAdmin(); // Enforce Admin Access
        $this->userModel = $this->model('User');
    }

    public function index() {
        $users = $this->userModel->getAllUsers();
        $data = [
            'active' => 'users',
            'users' => $users
        ];
        $this->view('users/index', $data);
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
             \App\Core\Csrf::verify();
             $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

             $data = [
                 'active' => 'users',
                 'id' => $id,
                 'role' => trim($_POST['role']),
                 'is_active' => isset($_POST['is_active']) ? 1 : 0
             ];

             if ($this->userModel->updateUserStatusRole($id, $data['role'], $data['is_active'])) {
                 Session::flash('user_msg', 'تم تحديث بيانات المستخدم بنجاح');
                 $this->redirect('users');
             } else {
                 die('خطأ في النظام');
             }

        } else {
            $user = $this->userModel->getUserById($id);
            if (!$user) {
                $this->redirect('users');
            }

            $data = [
                'active' => 'users',
                'user' => $user
            ];
            $this->view('users/edit', $data);
        }
    }
}
