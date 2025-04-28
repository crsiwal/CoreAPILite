<?php

use System\Middlewares\AuthMiddleware;
use System\Middlewares\CorsMiddleware;

// Define routes using the passed $router instance

// API Version 1 Routes
$router->group('/api/v1', function ($router) {


    // Auth routes
    $router->group('/auth', function ($router) {
        $router->post('/register', 'UserController@register');
        $router->post('/login', 'UserController@login');

        $router->group('/forgot-password', function ($router) {
            $router->post('/search', 'AuthController@forgotPasswordSearch');
            $router->post('/verify-otp', 'AuthController@verifyOtp');
            $router->post('/reset', 'AuthController@resetPassword');
        });
    });

    // User routes
    $router->group('/users', function ($router) {
        $router->get('/profile', 'UserController@profile', [AuthMiddleware::class]);
        $router->put('/update', 'UserController@update', [AuthMiddleware::class]);
    });
}, [CorsMiddleware::class]);
