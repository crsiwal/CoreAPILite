<?php

namespace App\Controllers;

use System\Core\BaseController;

class HomeController extends BaseController {
    // This is the method the route will call
    public function index() {
        echo "Home page";
    }

    // Another method (example)
    public function add($id) {
        echo "Add user";
        var_dump($id);
    }
}
