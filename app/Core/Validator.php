<?php

namespace App\Core;

class Validator {
    
    // Sanitize String
    public static function sanitize($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Validate Email
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Check Password Length
    public static function validatePassword($password) {
         return strlen($password) >= 6;
    }

    // Validate Phone
    public static function validatePhone($phone) {
        return preg_match('/^(70|71|73|77|78)[0-9]{7}$/', $phone);
    }

    // Validate Name (Arabic/English letters and spaces only)
    public static function validateName($name) {
        return preg_match('/^[\p{L}\s]+$/u', $name);
    }

    // Validate Positive Number
    public static function validatePositiveNumber($number) {
        return is_numeric($number) && $number > 0;
    }
}
