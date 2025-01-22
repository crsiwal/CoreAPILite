<?php
// Define the base path and application path
define('BASEPATH', dirname(dirname(__FILE__)) . '/');

// Initialize the router
use System\Core\Router;
use App\Configs\Constants;
use System\Core\Loader;

// Simple autoloader to load classes dynamically
spl_autoload_register(function ($class) {
    $file = BASEPATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        include_once $file;
    }
});

$router = new Router();

// Pass the $router object to routes.php
if (file_exists(Constants::CONFIGS_DIR_PATH . 'routes.php')) {
    include Constants::CONFIGS_DIR_PATH . 'routes.php';
}

Loader::init();

// Dispatch the request
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
