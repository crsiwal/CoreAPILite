# CoreAPILite

A lightweight, high-performance REST API framework for PHP applications. Created and maintained by Rahul Siwal.

## 🚀 Features

- Lightweight and fast REST API framework
- PSR-4 autoloading compliant
- Built-in routing system
- Database abstraction layer
- MVC architecture
- Easy configuration management
- Multilingual support
- Helper functions for common tasks
- Comprehensive testing suite

## 📋 Requirements

- PHP >= 7.4
- JSON extension
- PDO extension
- Composer (for dependency management)

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
- Copy `.env.example` to `.env`
- Update database and other configuration settings

## 📁 Project Structure

```
CoreAPILite/
├── app/
│   ├── Configs/      # Configuration files
│   ├── Controllers/  # Application controllers
│   ├── Helpers/      # Helper functions
│   ├── Languages/    # Language files
│   ├── Libraries/    # Core libraries
│   └── Models/       # Data models
├── public/           # Public assets and entry point
├── system/           # Core system files
├── tests/            # Test files
└── write/            # Writeable directories
```

## 🎯 Usage

### Basic API Endpoint

```php
// app/Controllers/ExampleController.php
class ExampleController extends BaseController {
    public function index() {
        return $this->response->json([
            'status' => 'success',
            'message' => 'Welcome to CoreAPILite!'
        ]);
    }
}
```

### Database Operations

```php
// app/Models/ExampleModel.php
class ExampleModel extends BaseModel {
    public function getData() {
        return $this->db->query("SELECT * FROM table_name")->fetchAll();
    }
}
```

## 🔧 Configuration

The framework uses a simple configuration system. All configuration files are located in `app/Configs/`. You can modify these files according to your needs.

## 🧪 Testing

The framework comes with PHPUnit for testing. Run tests using:

```bash
composer test
```

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

- **Rahul Siwal** - Creator and maintainer

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📫 Support

For support, please open an issue in the GitHub repository or contact the author.

---

Made with ❤️ by Rahul Siwal
