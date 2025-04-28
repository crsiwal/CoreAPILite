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

        // Check if identifier is phone number (basic validation)
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

    public function getById($id)
    {
        return $this->selectById($id);
    }

    public function emailExists($email)
    {
        $result = $this->select(['email' => $email], 'COUNT(*) as count');
        return $result[0]['count'] > 0;
    }

    public function usernameExists($username)
    {
        $result = $this->select(['username' => $username], 'COUNT(*) as count');
        return $result[0]['count'] > 0;
    }

    public function update($data, $conditions, $tableName = null)
    {
        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            $fields[]     = "{$key} = :{$key}";
            $params[$key] = $value;
        }

        $sql = "UPDATE " . ($tableName ?: $this->table) . " SET " . implode(', ', $fields);

        if (! empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', array_map(function ($key) {
                return "{$key} = :{$key}";
            }, array_keys($conditions)));
            $params = array_merge($params, $conditions);
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function updateLastLogin($userId)
    {
        return $this->update(
            ['last_login' => date('Y-m-d H:i:s')],
            ['id' => $userId]
        );
    }
}
