# Routing

CoreAPILite provides a simple and powerful routing system for defining your API endpoints.

## Basic Routing

Routes are defined in `app/Configs/routes.php`. The basic syntax is:

```php
$router->get('/path', 'Controller@method');
$router->post('/path', 'Controller@method');
$router->put('/path', 'Controller@method');
$router->delete('/path', 'Controller@method');
```

## Route Groups

You can group routes together for better organization and to apply middleware:

```php
$router->group('/api/v1', function ($router) {
    $router->get('/users', 'UserController@index');
    $router->post('/users', 'UserController@store');
}, [Middleware::class]);
```

## Middleware Integration

Middleware can be applied to individual routes or route groups:

```php
// Single route with middleware
$router->get('/profile', 'UserController@profile', [AuthMiddleware::class]);

// Group with middleware
$router->group('/admin', function ($router) {
    // Routes here
}, [AuthMiddleware::class, AdminMiddleware::class]);
```

## API Versioning

The framework supports API versioning through route groups:

```php
$router->group('/api/v1', function ($router) {
    // Version 1 routes
});

$router->group('/api/v2', function ($router) {
    // Version 2 routes
});
```

## Authentication Routes

Example of authentication routes:

```php
$router->group('/auth', function ($router) {
    $router->post('/register', 'UserController@register');
    $router->post('/login', 'UserController@login');

    $router->group('/forgot-password', function ($router) {
        $router->post('/search', 'AuthController@forgotPasswordSearch');
        $router->post('/verify-otp', 'AuthController@verifyOtp');
        $router->post('/reset', 'AuthController@resetPassword');
    });
});
```

## Protected Routes

Routes that require authentication:

```php
$router->group('/users', function ($router) {
    $router->get('/profile', 'UserController@profile', [AuthMiddleware::class]);
    $router->put('/update', 'UserController@update', [AuthMiddleware::class]);
});
```

## CORS Middleware

To enable CORS for your API:

```php
$router->group('/api', function ($router) {
    // Your API routes
}, [CorsMiddleware::class]);
```

## Route Parameters

You can define routes with parameters:

```php
$router->get('/users/{id}', 'UserController@show');
$router->put('/users/{id}', 'UserController@update');
```

## Route Naming

Best practices for route naming:

1. Use plural nouns for resources
2. Use HTTP verbs appropriately
3. Keep URLs clean and RESTful
4. Version your API
5. Group related routes

## Example Complete Route File

```php
<?php

use System\Middlewares\AuthMiddleware;
use System\Middlewares\CorsMiddleware;

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
```

## Next Steps

- [Controllers Documentation](../controllers/README.md)
- [Middleware Documentation](../middleware/README.md)
- [Authentication Documentation](../authentication/README.md)

---

Made with ❤️ by Rahul Siwal 