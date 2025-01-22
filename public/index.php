<?php
// Define the base path and application path
define('BASEPATH', dirname(dirname(__FILE__)));

// Initialize the router
use System\Core\Router;
use App\Configs\Constants;

echo "<pre>";
// Simple autoloader to load classes dynamically
spl_autoload_register(function ($class) {
    $file = BASEPATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        include_once $file;
    }
});


$router = new Router();

// var_dump(Constants::APP_PATH . 'Configs/routes.php');

// Pass the $router object to routes.php
if (file_exists(Constants::APP_PATH . 'Configs/routes.php')) {
    include Constants::APP_PATH . 'Configs/routes.php';
}

// Dispatch the request
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
