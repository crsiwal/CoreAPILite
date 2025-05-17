# Configuration

CoreAPILite uses a simple and flexible configuration system. All configuration files are located in the `app/Configs/` directory.

## Configuration Files

### Database Configuration

`app/Configs/database.php`:
```php
<?php

return [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'dbname' => 'database_name'
];
```

### Routes Configuration

`app/Configs/routes.php`:
```php
<?php

use System\Middlewares\AuthMiddleware;
use System\Middlewares\CorsMiddleware;

$router->group('/api/v1', function ($router) {
    // Your routes here
}, [CorsMiddleware::class]);
```

### Constants

`app/Configs/Constants.php`:
```php
<?php

namespace App\Configs;

class Constants {
    const APP_DIR_NAME = 'app';
    const LOGS_DIR_NAME = 'logs';
    const CONFIGS_DIR_NAME = 'Configs';
    const CONTROLLERS_DIR_NAME = 'Controllers';
    const HELPERS_DIR_NAME = 'Helpers';
    const LANGUAGES_DIR_NAME = 'Languages';
    const LIBRARIES_DIR_NAME = 'Libraries';
    const MODELS_DIR_NAME = 'Models';
    const PUBLIC_DIR_NAME = 'public';
    
    // Path constants
    const APP_PATH = BASEPATH . self::APP_DIR_NAME . DIRECTORY_SEPARATOR;
    const PUBLIC_DIR_PATH = BASEPATH . self::PUBLIC_DIR_NAME . DIRECTORY_SEPARATOR;
    const CONFIGS_DIR_PATH = self::APP_PATH . self::CONFIGS_DIR_NAME . DIRECTORY_SEPARATOR;
    const CONTROLLERS_DIR_PATH = self::APP_PATH . self::CONTROLLERS_DIR_NAME . DIRECTORY_SEPARATOR;
    const HELPERS_DIR_PATH = self::APP_PATH . self::HELPERS_DIR_NAME . DIRECTORY_SEPARATOR;
    const LANGUAGES_DIR_PATH = self::APP_PATH . self::LANGUAGES_DIR_NAME . DIRECTORY_SEPARATOR;
    const LIBRARIES_DIR_PATH = self::APP_PATH . self::LIBRARIES_DIR_NAME . DIRECTORY_SEPARATOR;
    const MODELS_DIR_PATH = self::APP_PATH . self::MODELS_DIR_NAME . DIRECTORY_SEPARATOR;

    // Logs Constants
    const LOGS_LEVEL = 1;
    const LOGS_DIR_PATH = BASEPATH . "write" . DIRECTORY_SEPARATOR . self::LOGS_DIR_NAME . DIRECTORY_SEPARATOR;
}
```

### Response Codes

`app/Configs/ResponseCode.php`:
```php
<?php
namespace App\Configs;

class ResponseCode
{
    // Success codes
    const SUCCESS = 200;
    const CREATED = 201;
    const UPDATED = 200;
    const DELETED = 200;

    // Error codes
    const INVALID_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const CONFLICT = 409;
    const TOO_MANY_REQUESTS = 429;
    const INTERNAL_SERVER_ERROR = 500;

    // Authentication specific codes
    const INVALID_CREDENTIALS = 401;
    const INVALID_TOKEN = 401;
    const TOKEN_EXPIRED = 401;
    const EMAIL_ALREADY_EXISTS = 409;
    const USER_NOT_FOUND = 404;
    const INVALID_OTP = 400;

    // Database specific codes
    const MYSQL_QUERY_FAILED = 500;
    const DUPLICATE_ENTRY = 409;
}
```

## Environment Configuration

Create a `.env` file in your project root:

```env
DB_HOST=localhost
DB_NAME=your_database
DB_USER=your_username
DB_PASS=your_password
APP_ENV=development
APP_DEBUG=true
```

## Using Configuration Values

### Accessing Database Configuration

```php
$config = require 'app/Configs/database.php';
$db = new Database($config);
```

### Using Constants

```php
use App\Configs\Constants;

$appPath = Constants::APP_PATH;
$configPath = Constants::CONFIGS_DIR_PATH;
```

### Using Response Codes

```php
use App\Configs\ResponseCode;

return $this->response->json($data, ResponseCode::SUCCESS);
return $this->response->json($error, ResponseCode::NOT_FOUND);
```

## Best Practices

1. **Security**
   - Never commit sensitive data to version control
   - Use environment variables for sensitive data
   - Keep configuration files organized

2. **Organization**
   - Group related configurations
   - Use meaningful constant names
   - Document configuration options

3. **Maintenance**
   - Keep configurations up to date
   - Document changes
   - Use version control for configuration files

4. **Performance**
   - Cache configuration values when possible
   - Minimize configuration file size
   - Use appropriate data types

## Example Configuration Usage

### Database Connection

```php
class Database
{
    private $config;

    public function __construct()
    {
        $this->config = require Constants::CONFIGS_DIR_PATH . 'database.php';
    }

    public function connect()
    {
        $dsn = "mysql:host={$this->config['host']};dbname={$this->config['dbname']}";
        return new PDO($dsn, $this->config['username'], $this->config['password']);
    }
}
```

### Response Handling

```php
class Controller
{
    protected function success($data)
    {
        return $this->response->json([
            'status' => 'success',
            'data' => $data
        ], ResponseCode::SUCCESS);
    }

    protected function error($message, $code = ResponseCode::INTERNAL_SERVER_ERROR)
    {
        return $this->response->json([
            'status' => 'error',
            'message' => $message
        ], $code);
    }
}
```

## Next Steps

- [Database Documentation](../database/README.md)
- [Controllers Documentation](../controllers/README.md)
- [Models Documentation](../models/README.md)

---

Made with ❤️ by Rahul Siwal 