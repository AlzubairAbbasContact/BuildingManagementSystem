<?php
// Load Config
require_once '../app/Config/config.php';

// Turn off error reporting for production
error_reporting(0);
ini_set('display_errors', 0);

// Autoloader
spl_autoload_register(function ($className) {
    // Convert namespace separators to directory separators
    $classPath = str_replace(['App\\', '\\'], ['', DIRECTORY_SEPARATOR], $className);
    $file = APP_ROOT . '/app/' . $classPath . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});

// Init Core Library
// Init Core Library
App\Core\Session::init();
$init = new App\Core\Router();
