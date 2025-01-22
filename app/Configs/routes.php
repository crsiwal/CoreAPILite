<?php

// Define routes using the passed $router instance

// $router->get('/users', 'HomeController', 'index');

$router->group('/users')
    ->get('/', 'HomeController', 'index')
    ->get('/add', 'HomeController', 'add')
    ->get('/add/{id:num}', 'HomeController', 'add');
