<?php

class Router {

    /**
     * Routing processing. All requests go through this function and matches it to the correct functions
     *
     * @param string $uri
     * @return string
     */
    public function proccess($uri) {
        // Clean params from URI
        $uri = explode('?', explode('&', $uri)[0])[0];
        $parts = explode('/', $uri);

        // Set controller and action from url (default home/index)
        $controller = isset($parts[1]) ? ucfirst(strtolower($parts[1])) : '';
        $controller = empty($controller) ? 'Home' : $controller;
        $method = isset($parts[2]) ? $parts[2] : 'index';
        $method = empty($method) ? 'index' : $method;

        // Check for nasty chars
        if(preg_match('/[^A-Za-z0-9]/', $controller) ||
           preg_match('/[^A-Za-z0-9]/', $method)) {
            return $this->exitPage(403);
        }

        // Get controller
        if(!file_exists(__DIR__ . "/../app/controllers/$controller.php")) {
            return $this->exitPage(404);
        }
        require_once(__DIR__ . "/../app/controllers/$controller.php");

        // Check for invalid controller/methods
        if(!class_exists($controller)) {
            return $this->exitPage(403);
        }

        if(!method_exists($controller, $method)){
            return $this->exitPage(404);
        }

        $args = isset($parts[3]) ? [$parts[3]] : [];

        // Good to go, send method and args to the correct controller
        $this->controller = new $controller;
        return call_user_func_array([$this->controller, $method], $args);
    }

    /**
     * Returns an simple page and stops all other processes.
     *
     * @param string $code
     * @return string
     */
    private function exitPage($code) {
        echo "<h1>$code</h1>";
        exit();
    }
}