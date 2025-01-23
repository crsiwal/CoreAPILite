<?php

// Define routes using the passed $router instance

// $router->get('/users', 'HomeController', 'index');

$router->group('/users')
    ->get('/', 'Home', 'index')
    ->get('/add', 'Home', 'add')
    ->get('/add/{id:num}', 'Home', 'add');
