# Models

Models in CoreAPILite provide an easy way to interact with your database tables. Each model represents a database table and provides methods for common database operations.

## Basic Model Structure

```php
<?php
namespace App\Models;

use System\Core\Model;

class User extends Model
{
    public function __construct()
    {
        parent::__construct("users", __CLASS__);
    }
}
```

## Database Operations

### Basic CRUD Operations

```php
class User extends Model
{
    public function __construct()
    {
        parent::__construct("users", __CLASS__);
    }

    // Create
    public function create($data)
    {
        return $this->insert($data);
    }

    // Read
    public function find($id)
    {
        return $this->selectById($id);
    }

    // Update
    public function update($data, $conditions)
    {
        return $this->update($data, $conditions);
    }

    // Delete
    public function delete($conditions)
    {
        return $this->delete($conditions);
    }
}
```

## Advanced Query Methods

### Custom Select Queries

```php
class User extends Model
{
    public function getActiveUsers()
    {
        return $this->select(['status' => 'active']);
    }

    public function getUsersByRole($role)
    {
        return $this->select(['role' => $role], 'id, name, email');
    }
}
```

### Complex Queries

```php
class User extends Model
{
    public function searchUsers($keyword)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE name LIKE :keyword 
                OR email LIKE :keyword 
                OR phone LIKE :keyword";
        
        $params = ['keyword' => "%{$keyword}%"];
        return $this->query($sql, $params);
    }
}
```

## Example User Model

```php
<?php
namespace App\Models;

use System\Core\Model;

class User extends Model
{
    public function __construct()
    {
        parent::__construct("users", __CLASS__);
    }

    public function findByIdentifier($identifier)
    {
        // Check if identifier is email
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return $this->getByEmail($identifier);
        }

        // Check if identifier is phone number
        if (preg_match('/^\+?[1-9]\d{1,14}$/', $identifier)) {
            return $this->getByPhone($identifier);
        }

        // Assume it's a username
        return $this->getByUsername($identifier);
    }

    public function getByEmail($email)
    {
        return $this->selectOne(['email' => $email]);
    }

    public function getByPhone($phone)
    {
        return $this->selectOne(['phone' => $phone]);
    }

    public function getByUsername($username)
    {
        return $this->selectOne(['username' => $username]);
    }

    public function emailExists($email)
    {
        $result = $this->select(['email' => $email], 'COUNT(*) as count');
        return $result[0]['count'] > 0;
    }

    public function updateLastLogin($userId)
    {
        return $this->update(
            ['last_login' => date('Y-m-d H:i:s')],
            ['id' => $userId]
        );
    }
}
```

## Best Practices

1. **Model Naming**
   - Use singular form for model names
   - Match model name with table name
   - Follow PSR-4 autoloading standards

2. **Query Building**
   - Use prepared statements
   - Sanitize input data
   - Use proper indexing

3. **Method Organization**
   - Group related methods
   - Use descriptive method names
   - Keep methods focused

4. **Error Handling**
   - Use try-catch blocks
   - Log database errors
   - Return appropriate responses

5. **Performance**
   - Use proper indexes
   - Optimize queries
   - Cache frequently accessed data

## Using Models in Controllers

```php
class UserController extends Controller
{
    public function show($id)
    {
        $user = (new User())->find($id);
        
        if (!$user) {
            return $this->response->json([
                'status' => 'error',
                'message' => 'User not found'
            ], ResponseCode::NOT_FOUND);
        }

        return $this->response->json([
            'status' => 'success',
            'data' => $user
        ]);
    }
}
```

## Next Steps

- [Database Documentation](../database/README.md)
- [Controllers Documentation](../controllers/README.md)
- [Validation Documentation](../validation/README.md)

---

Made with ❤️ by Rahul Siwal 