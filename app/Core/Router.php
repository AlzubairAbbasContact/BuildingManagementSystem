<?php

namespace App\Core;

class Router {
    protected $currentController = 'AuthController'; // Default controller (Login usually)
    protected $currentMethod = 'login'; // Default method
    protected $params = [];

    public function __construct() {
        $url = $this->getUrl();

        // Look in controllers for first value
        if (isset($url[0])) {
            $controllerName = ucwords($url[0]) . 'Controller';
            if (file_exists('../app/Controllers/' . $controllerName . '.php')) {
                $this->currentController = $controllerName;
                $this->currentMethod = 'index'; // Reset default method when controller changes
                unset($url[0]);
            }
        }

        // Require the controller
        // Autoloader will handle this if we use namespaces correctly, 
        // but for now let's just instantiate since autoloader is active.
        $controllerClass = 'App\\Controllers\\' . $this->currentController;
        $this->currentController = new $controllerClass();

        // Check for second part of url
        if (isset($url[1])) {
            // Check to see if method exists in controller
            if (method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                unset($url[1]);
            }
        }

        // Get params
        $this->params = $url ? array_values($url) : [];

        // Call a callback with array of params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}
