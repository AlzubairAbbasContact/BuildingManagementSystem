<?php

namespace App\Core;

class Session {
    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Set Flash Message
    public static function flash($name, $string = '', $class = 'alert alert-success') {
        if (!empty($name)) {
            if (!empty($string) && empty($_SESSION[$name])) {
                if (!empty($_SESSION[$name . '_class'])) {
                    unset($_SESSION[$name . '_class']);
                }
                $_SESSION[$name] = $string;
                $_SESSION[$name . '_class'] = $class;
            } elseif (empty($string) && !empty($_SESSION[$name])) {
                $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
                echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$name] . '</div>';
                unset($_SESSION[$name]);
                unset($_SESSION[$name . '_class']);
            }
        }
    }

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public static function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin';
    }

    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: ' . URL_ROOT . '/auth/login');
            exit;
        }
        
        // Prevent caching for protected pages
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
    }

    public static function requireAdmin() {
        self::requireLogin();
        if (!self::isAdmin()) {
            // Redirect to dashboard with error or 403
            header('Location: ' . URL_ROOT . '/dashboard');
            exit;
        }
    }

    public static function destroy() {
        // Unset all session values
        $_SESSION = [];

        // Destroy the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destroy the actual session
        session_destroy();
    }
}
