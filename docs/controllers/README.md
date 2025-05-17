# Controllers

Controllers in CoreAPILite handle the HTTP requests and return responses for your API endpoints.

## Basic Controller Structure

```php
<?php

namespace App\Controllers;

use System\Core\Controller;

class Home extends Controller {
    public function index() {
        echo "Welcome to CoreAPILite!";
    }
}
```

## Controller Methods

### Basic Methods

```php
class UserController extends Controller {
    public function index() {
        // List all users
    }

    public function show($id) {
        // Show specific user
    }

    public function store() {
        // Create new user
    }

    public function update($id) {
        // Update user
    }

    public function destroy($id) {
        // Delete user
    }
}
```

## Request Handling

### Accessing Request Data

```php
class UserController extends Controller {
    public function store() {
        $data = $this->request->all();
        $name = $this->request->input('name');
        $email = $this->request->input('email');
        
        // Process the data
    }
}
```

### Input Validation

```php
use App\Libraries\Validation;

class UserController extends Controller {
    public function store() {
        $data = $this->request->all();
        
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ];
        
        $errors = Validation::validate($data, $rules);
        
        if (!empty($errors)) {
            return $this->response->json([
                'status' => 'error',
                'errors' => $errors
            ], ResponseCode::INVALID_REQUEST);
        }
        
        // Process valid data
    }
}
```

## Response Methods

### JSON Response

```php
return $this->response->json([
    'status' => 'success',
    'data' => $data
]);
```

### Status Codes

```php
use App\Configs\ResponseCode;

return $this->response->json($data, ResponseCode::CREATED);
return $this->response->json($data, ResponseCode::NOT_FOUND);
```

## Authentication Controller Example

```php
<?php

namespace App\Controllers;

use System\Core\Controller;
use App\Configs\ResponseCode;
use App\Models\User;

class AuthController extends Controller {
    public function login() {
        $data = $this->request->all();
        
        $rules = [
            'identifier' => 'required',
            'password' => 'required'
        ];
        
        $errors = Validation::validate($data, $rules);
        
        if (!empty($errors)) {
            return $this->response->json([
                'status' => 'error',
                'errors' => $errors
            ], ResponseCode::INVALID_REQUEST);
        }
        
        $user = (new User())->findByIdentifier($data['identifier']);
        
        if (!$user || !password_verify($data['password'], $user['password'])) {
            return $this->response->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], ResponseCode::INVALID_CREDENTIALS);
        }
        
        // Generate token and return response
        return $this->response->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ]);
    }
}
```

## Best Practices

1. **Keep Controllers Thin**
   - Move business logic to models
   - Controllers should only handle HTTP concerns

2. **Use Proper Namespacing**
   - Always use the correct namespace
   - Follow PSR-4 autoloading standards

3. **Handle Errors Gracefully**
   - Use try-catch blocks
   - Return appropriate error responses
   - Use the ResponseCode constants

4. **Validate Input**
   - Always validate incoming data
   - Use the Validation library
   - Return clear error messages

5. **Use Proper HTTP Methods**
   - GET for retrieving data
   - POST for creating data
   - PUT for updating data
   - DELETE for removing data

## Next Steps

- [Models Documentation](../models/README.md)
- [Validation Documentation](../validation/README.md)
- [Response Codes Documentation](../response-codes/README.md)

---

Made with ❤️ by Rahul Siwal 