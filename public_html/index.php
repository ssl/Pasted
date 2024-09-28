<?php

// Load all required files
require __DIR__ . '/../system/Autoload.php';

if(debug) {
    // Display all errors if in debug mode
    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);
}

// Start routing
$router = new Router();
echo $router->proccess($_SERVER['REQUEST_URI']);
