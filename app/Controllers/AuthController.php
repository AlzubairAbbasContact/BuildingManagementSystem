<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Validator;
use App\Core\Session;

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        // If user is already logged in, redirect to dashboard
        // But allow logout and register (for admins) actions to proceed
        $url = $_GET['url'] ?? '';
        if (Session::isLoggedIn() && strpos($url, 'auth/logout') === false && strpos($url, 'auth/register') === false) {
             $this->redirect('dashboard/index');
        }

        $this->userModel = $this->model('User');
    }

    // Register User (Admin Only)
    public function register() {
         // Check if Admin
         if (!Session::isAdmin()) {
             $this->redirect('auth/login'); // Or dashboard if logged in, but safe fallback
         }

         // Check for POST (Existing Logic...)
         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
              // ... existing post logic
             \App\Core\Csrf::verify();
             $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
             
             $data = [
                 'active' => 'users', // Active menu item
                 'name' => trim($_POST['name']),
                 'email' => trim($_POST['email']),
                 'password' => trim($_POST['password']),
                 'confirm_password' => trim($_POST['confirm_password']),
                 'phone' => trim($_POST['phone']),
                 'role' => trim($_POST['role']), // New Field
                 'is_active' => isset($_POST['is_active']) ? 1 : 0, // New Field
                 'image' => null,
                 'name_err' => '',
                 'email_err' => '',
                 'password_err' => '',
                 'confirm_password_err' => '',
                 'phone_err' => '',
                 'image_err' => ''
             ];

             // Validate Email
             if (empty($data['email'])) {
                 $data['email_err'] = 'الرجاء إدخال البريد الإلكتروني';
             } elseif (!Validator::validateEmail($data['email'])) {
                 $data['email_err'] = 'صيغة البريد الإلكتروني غير صحيحة';
             } elseif ($this->userModel->findUserByEmail($data['email'])) {
                 $data['email_err'] = 'البريد الإلكتروني مسجل بالفعل';
             }
 
             // Validate Name
             if (empty($data['name'])) {
                 $data['name_err'] = 'الرجاء إدخال الاسم';
             } elseif (!Validator::validateName($data['name'])) {
                 $data['name_err'] = 'الاسم يجب أن يحتوي على حروف فقط';
             }
 
             // Validate Password
             if (empty($data['password'])) {
                 $data['password_err'] = 'الرجاء إدخال كلمة المرور';
             } elseif (strlen($data['password']) < 6) {
                 $data['password_err'] = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
             }
 
             // Validate Confirm Password
             if (empty($data['confirm_password'])) {
                 $data['confirm_password_err'] = 'الرجاء تأكيد كلمة المرور';
             } else {
                 if ($data['password'] != $data['confirm_password']) {
                     $data['confirm_password_err'] = 'كلمات المرور غير متطابقة';
                 }
             }

             // Validate Phone
             if (empty($data['phone'])) {
                 $data['phone_err'] = 'الرجاء إدخال رقم الهاتف';
             } elseif (!Validator::validatePhone($data['phone'])) {
                 $data['phone_err'] = 'رقم الهاتف يجب أن يكون 9 أرقام ويبدأ بـ 7';
             }

             // Handle Image Upload
             if (!empty($_FILES['image']['name'])) {
                 // ... (Keep existing image logic)
                 $allowed = ['jpg', 'jpeg', 'png'];
                 $filename = $_FILES['image']['name'];
                 $filetmp = $_FILES['image']['tmp_name'];
                 $filesize = $_FILES['image']['size'];
                 $fileext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                 if (!in_array($fileext, $allowed)) {
                     $data['image_err'] = 'صيغة الصورة غير مدعومة. فقط JPG, PNG';
                 }
                 
                 if (empty($data['image_err'])) {
                     $mime = mime_content_type($filetmp);
                     $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'];
                     if (!in_array($mime, $allowedMimes)) {
                         $data['image_err'] = 'الملف المرفق ليس صورة صالحة';
                     }
                 }

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
 
             if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err']) && empty($data['phone_err']) && empty($data['image_err'])) {
                 // Hash Password
                 $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
 
                 // Register User
                 if ($this->userModel->register($data)) {
                     Session::flash('user_msg', 'تم إضافة المستخدم بنجاح');
                     $this->redirect('users'); // Redirect to Users List
                 } else {
                     die('حدث خطأ ما');
                 }
 
             } else {
                 $this->view('auth/register', $data);
             }
 
         } else {
             $data = [
                 'active' => 'users',
                 'name' => '',
                 'email' => '',
                 'password' => '',
                 'confirm_password' => '',
                 'phone' => '',
                 'role' => 'user',
                 'is_active' => 1,
                 'image' => '',
                 'name_err' => '',
                 'email_err' => '',
                 'password_err' => '',
                 'confirm_password_err' => '',
                 'phone_err' => '',
                 'image_err' => ''
             ];
             $this->view('auth/register', $data);
         }
    }

    public function login() {
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            \App\Core\Csrf::verify();
            // Process form
            // Sanitize POST data
            
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',
            ];

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'الرجاء إدخال البريد الإلكتروني';
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'الرجاء إدخال كلمة المرور';
            }

            // Check for user/email
            if ($this->userModel->findUserByEmail($data['email'])) {
                // User found
            } else {
                $data['email_err'] = 'لا يوجد حساب بهذا البريد الإلكتروني';
            }

            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['password_err'])) {
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if ($loggedInUser) {
                    // Check if Active
                    if ($loggedInUser->is_active == 0) {
                         $data['email_err'] = 'هذا الحساب غير نشط. يرجى مراجعة الإدارة.';
                         $this->view('auth/login', $data);
                    } else {
                        // Create Session
                        $this->createUserSession($loggedInUser);
                    }
                } else {
                    $data['password_err'] = 'كلمة المرور غير صحيحة';
                    $this->view('auth/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('auth/login', $data);
            }

        } else {
            // Init data
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            ];

            // Load view
            $this->view('auth/login', $data);
        }
    }

    public function createUserSession($user) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_image'] = $user->image;
        $_SESSION['user_role'] = $user->role;
        session_write_close();
        $this->redirect('dashboard/index');
    }

    public function logout() {
        Session::destroy();
        $this->redirect('auth/login');
    }
}
