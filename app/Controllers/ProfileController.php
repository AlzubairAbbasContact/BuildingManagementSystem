<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;

class ProfileController extends Controller {
    private $userModel;

    public function __construct() {
        Session::requireLogin();
        $this->userModel = $this->model('User');
    }

    public function index() {
        $user = $this->userModel->getUserById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
             \App\Core\Csrf::verify();
             $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
             
             $data = [
                 'active' => 'profile',
                 'id' => $_SESSION['user_id'],
                 'name' => trim($_POST['name']),
                 'email' => trim($_POST['email']),
                 'phone' => trim($_POST['phone']),
                 'image' => $user->image,
                 'name_err' => '',
                 'email_err' => '',
                 'phone_err' => '',
                 'image_err' => ''
             ];

             // Validate Name
             if (empty($data['name'])) {
                 $data['name_err'] = 'الاسم مطلوب';
             } elseif (!Validator::validateName($data['name'])) {
                 $data['name_err'] = 'الاسم يجب أن يحتوي على حروف فقط';
             }

             // Validate Email
             if (empty($data['email'])) {
                 $data['email_err'] = 'البريد الإلكتروني مطلوب';
             } elseif (!Validator::validateEmail($data['email'])) {
                  $data['email_err'] = 'صيغة البريد الإلكتروني غير صحيحة';
             }

             // Validate Phone
             if (empty($data['phone'])) {
                 $data['phone_err'] = 'رقم الهاتف مطلوب';
             } elseif (!Validator::validatePhone($data['phone'])) {
                 $data['phone_err'] = 'رقم الهاتف يجب أن يكون 9 أرقام ويبدأ بـ 7';
             }

             // Handle Image Upload
             if (!empty($_FILES['image']['name'])) {
                 $allowed = ['jpg', 'jpeg', 'png'];
                 $filename = $_FILES['image']['name'];
                 $filetmp = $_FILES['image']['tmp_name'];
                 $filesize = $_FILES['image']['size'];
                 $fileext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                 // Check Extension
                 if (!in_array($fileext, $allowed)) {
                     $data['image_err'] = 'صيغة الصورة غير مدعومة. فقط JPG, PNG';
                 }
                 
                 // Strict MIME Type Check
                 if (empty($data['image_err'])) {
                     $mime = mime_content_type($filetmp);
                     $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'];
                     if (!in_array($mime, $allowedMimes)) {
                         $data['image_err'] = 'الملف المرفق ليس صورة صالحة';
                     }
                 }

                 // Check Size (2MB)
                 if ($filesize > 2097152) {
                     $data['image_err'] = 'حجم الصورة كبير جداً';
                 }

                 if (empty($data['image_err'])) {
                     $newFilename = uniqid() . '.' . $fileext;
                     $destination = '../public/uploads/' . $newFilename;
                     
                     if (move_uploaded_file($filetmp, $destination)) {
                         $data['image'] = $newFilename;
                     } else {
                         $data['image_err'] = 'فشل في رفع الصورة';
                     }
                 }
             }

             if (empty($data['name_err']) && empty($data['email_err']) && empty($data['image_err']) && empty($data['phone_err'])) {
                 // Update User Logic (Need to add update method in User Model)
                 // For now, let's assume we implement it or query directly?
                 // Best practice: Add method to Model.
                 
                 $this->userModel->updateProfile($data); 
                 
                 // Update Session
                 $_SESSION['user_name'] = $data['name'];
                 $_SESSION['user_email'] = $data['email'];
                 $_SESSION['user_image'] = $data['image'];

                 Session::flash('profile_msg', 'تم تحديث الملف الشخصي');
                 $this->redirect('profile');

             } else {
                 $this->view('profile/index', $data);
             }

        } else {
            $data = [
                'active' => 'profile',
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'image' => $user->image,
                'name_err' => '',
                'email_err' => '',
                'phone_err' => '',
                'image_err' => ''
            ];
            $this->view('profile/index', $data);
        }
    }
}
