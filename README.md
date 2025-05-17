# CoreAPILite

A lightweight and powerful REST API framework for PHP applications. CoreAPILite provides a simple yet robust foundation for building modern web APIs with ease.

## 🚀 Features

- **Simple Routing System**: Easy-to-use routing with support for route groups and middleware
- **MVC Architecture**: Clean separation of concerns with Models, Views, and Controllers
- **Database Abstraction**: Powerful database operations with prepared statements
- **Authentication**: Built-in user authentication and token management
- **Validation**: Comprehensive input validation system
- **Response Handling**: Standardized response codes and formats
- **Middleware Support**: Flexible middleware system for request processing
- **Configuration Management**: Easy configuration through environment files

## 📋 Requirements

- PHP >= 7.4
- MySQL/MariaDB
- Apache/Nginx
- Composer

## 🛠 Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/CoreAPILite.git
```

2. Install dependencies:
```bash
composer install
```

3. Configure your environment:
```bash
cp .env.example .env
```

4. Update the `.env` file with your database credentials and other settings.

## 📁 Project Structure

```
app/
├── Configs/          # Configuration files
│   ├── routes.php    # Route definitions
│   ├── database.php  # Database configuration
│   ├── Constants.php # Framework constants
│   └── ResponseCode.php # HTTP response codes
├── Controllers/      # Application controllers
├── Models/           # Data models
├── Libraries/        # Core libraries
├── Helpers/          # Helper functions
└── Languages/        # Language files
```

## 📚 Documentation

Comprehensive documentation is available in the `docs/` directory:

- [Getting Started](docs/getting-started/README.md)
- [Routing](docs/routing/README.md)
- [Controllers](docs/controllers/README.md)
- [Models](docs/models/README.md)
- [Database](docs/database/README.md)
- [Authentication](docs/authentication/README.md)
- [Validation](docs/validation/README.md)
- [Response Codes](docs/response-codes/README.md)
- [Middleware](docs/middleware/README.md)
- [Configuration](docs/configuration/README.md)

## 💡 Quick Start

### Basic Controller Example

```php
namespace App\Controllers;

use System\Core\Controller;

class Home extends Controller {
    public function index() {
        return $this->response->json([
            'status' => 'success',
            'message' => 'Welcome to CoreAPILite!'
        ]);
    }
}
```

### Basic Model Example

```php
namespace App\Models;

use System\Core\Model;

class User extends Model {
    public function __construct() {
        parent::__construct("users", __CLASS__);
    }
}
```

## 🔐 Security

- Input validation and sanitization
- Prepared statements for database queries
- CSRF protection
- Secure password hashing
- Token-based authentication

## 🚀 Performance

- Optimized database queries
- Caching support
- Efficient routing system
- Lightweight core

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📫 Support

For support, please:
- Check the [GitHub repository](https://github.com/yourusername/CoreAPILite)
- Create an issue
- Contact the maintainer

---

Made with ❤️ by Rahul Siwal
