<?php

namespace App\Core;

class Csrf {
    // Generate Token if not exists
    public static function generate() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // Output hidden input field
    public static function field() {
        $token = self::generate();
        echo '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

    // Verify Token manually
    public static function verify() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                // Log invalid attempt
                // die or redirect
                die('CSRF Validation Failed: Invalid Request'); // In production, show a nice 403 page
            }
        }
        return true;
    }
}
