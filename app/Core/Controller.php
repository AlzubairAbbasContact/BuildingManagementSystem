<?php

namespace App\Core;

class Controller {
    // Load Model
    public function model($model) {
        // Assume autoloader handles namespace, but here we instantiate dynamically
        // Models are in App\Models namespace
        $modelClass = 'App\\Models\\' . $model;
        return new $modelClass();
    }

    // Load View
    public function view($view, $data = []) {
        // Extract data to variables
        if (!empty($data)) {
            extract($data);
        }
        
        // Check for view file
        if (file_exists('../views/' . $view . '.php')) {
            require_once '../views/' . $view . '.php';
        } else {
            // View does not exist
            die("View does not exist: " . $view);
        }
    }
    
    // Redirect helper
    public function redirect($url) {
        header('Location: ' . URL_ROOT . '/' . $url);
        exit;
    }
}
