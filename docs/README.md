# CoreAPILite Documentation

Welcome to the official documentation for CoreAPILite, a lightweight and powerful REST API framework for PHP applications.

## 📚 Documentation Sections

1. [Getting Started](getting-started/README.md)
   - Installation
   - Project Structure
   - Basic Configuration

2. [Routing](routing/README.md)
   - Route Groups
   - Middleware Integration
   - API Versioning

3. [Controllers](controllers/README.md)
   - Basic Controllers
   - Request Handling
   - Response Methods

4. [Models](models/README.md)
   - Model Basics
   - Database Operations
   - Custom Methods

5. [Database](database/README.md)
   - Configuration
   - Query Building
   - Transactions

6. [Authentication](authentication/README.md)
   - User Authentication
   - Token Management
   - Password Reset

7. [Validation](validation/README.md)
   - Input Validation
   - Custom Rules
   - Error Handling

8. [Response Codes](response-codes/README.md)
   - HTTP Status Codes
   - Custom Response Codes
   - Error Handling

9. [Middleware](middleware/README.md)
   - Creating Middleware
   - Authentication Middleware
   - CORS Middleware

10. [Configuration](configuration/README.md)
    - Environment Setup
    - Database Configuration
    - Constants

## 🔍 Quick Start

```php
// Basic Controller Example
namespace App\Controllers;

use System\Core\Controller;

class Home extends Controller {
    public function index() {
        echo "Welcome to CoreAPILite!";
    }
}
```

## 📝 Project Structure

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

## 🛠 Requirements

- PHP >= 7.4
- MySQL/MariaDB
- Apache/Nginx
- Composer

## 📫 Support

For support, please:
- Check the GitHub repository
- Contact the maintainer
- Join the community

---

Made with ❤️ by Rahul Siwal 