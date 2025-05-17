# Database

CoreAPILite provides a simple and powerful database abstraction layer for interacting with your database.

## Configuration

Database configuration is stored in `app/Configs/database.php`:

```php
<?php

return [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'dbname' => 'database_name'
];
```

## Basic Database Operations

### Connecting to Database

```php
use System\Core\Database;

$db = new Database();
$db->connect();
```

### Basic Queries

```php
// Select
$result = $db->query("SELECT * FROM users WHERE id = :id", ['id' => 1]);

// Insert
$db->query("INSERT INTO users (name, email) VALUES (:name, :email)", [
    'name' => 'John Doe',
    'email' => 'john@example.com'
]);

// Update
$db->query("UPDATE users SET name = :name WHERE id = :id", [
    'name' => 'John Doe',
    'id' => 1
]);

// Delete
$db->query("DELETE FROM users WHERE id = :id", ['id' => 1]);
```

## Using Models for Database Operations

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

## Advanced Database Features

### Transactions

```php
try {
    $db->beginTransaction();

    // Perform multiple operations
    $db->query("INSERT INTO users (name) VALUES (:name)", ['name' => 'John']);
    $db->query("INSERT INTO profiles (user_id) VALUES (:user_id)", ['user_id' => $db->lastInsertId()]);

    $db->commit();
} catch (Exception $e) {
    $db->rollBack();
    throw $e;
}
```

### Prepared Statements

```php
$stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => 1]);
$result = $stmt->fetch();
```

### Query Builder

```php
class User extends Model
{
    public function search($keyword)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE name LIKE :keyword 
                OR email LIKE :keyword";
        
        return $this->query($sql, ['keyword' => "%{$keyword}%"]);
    }
}
```

## Best Practices

1. **Security**
   - Always use prepared statements
   - Validate and sanitize input
   - Use proper escaping

2. **Performance**
   - Use proper indexes
   - Optimize queries
   - Use transactions for multiple operations

3. **Error Handling**
   - Use try-catch blocks
   - Log database errors
   - Handle connection failures

4. **Maintenance**
   - Regular backups
   - Database optimization
   - Index maintenance

## Example Database Operations

### Complex Query Example

```php
class User extends Model
{
    public function getUsersWithProfile()
    {
        $sql = "SELECT u.*, p.* 
                FROM users u 
                LEFT JOIN profiles p ON u.id = p.user_id 
                WHERE u.status = :status";
        
        return $this->query($sql, ['status' => 'active']);
    }
}
```

### Transaction Example

```php
class User extends Model
{
    public function createWithProfile($userData, $profileData)
    {
        try {
            $this->db->beginTransaction();

            // Create user
            $userId = $this->insert($userData);

            // Create profile
            $profileData['user_id'] = $userId;
            $this->db->query(
                "INSERT INTO profiles (user_id, bio) VALUES (:user_id, :bio)",
                $profileData
            );

            $this->db->commit();
            return $userId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
```

## Next Steps

- [Models Documentation](../models/README.md)
- [Controllers Documentation](../controllers/README.md)
- [Configuration Documentation](../configuration/README.md)

---

Made with ❤️ by Rahul Siwal 