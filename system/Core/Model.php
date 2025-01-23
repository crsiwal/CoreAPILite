<?php

/**
 * Model class
 *
 * This class serves as the base model for the application.
 * It provides common functionality that can be used by other models.
 *
 * @package System\Core
 */

namespace System\Core;

use PDO;
use PDOException;
use App\Configs\Constants;

class Model {
    protected $table; // Table name to be set by child model
    protected $db;

    /**
     * Constructor
     *
     * Creates a new PDO connection to the database.
     *
     * @param string $table (Optional) The name of the table to use for the model.
     */
    public function __construct($table = null) {
        try {
            $config = include Constants::CONFIGS_DIR_PATH . 'database.php';
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']}";
            $this->db = new PDO($dsn, $config['username'], $config['password']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->table = $table;
            return $conn;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    /**
     * Sets the table name for the model.
     *
     * @param string $table The name of the table to set.
     */
    public function setTable($table) {
        $this->table = $table;
    }

    /**
     * Selects records from a specified table with optional conditions and columns.
     *
     * @param array $conditions (Optional) An associative array of conditions where the key is the column name and the value is the value to match.
     * @param string|array $columns (Optional) The columns to select, either as a comma-separated string or an array of column names. Defaults to '*'.
     * @param string $tableName (Optional) The name of the table to select from.
     * @return array An array of associative arrays representing the selected records.
     *
     * @example
     * // Select all columns from the 'users' table where 'id' is 1
     * $result = $this->select(['id' => 1]);
     *
     * @example
     * // Select 'name' and 'email' columns from the 'users' table where 'status' is 'active'
     * $result = $this->select(['status' => 'active'], 'name, email');
     */
    public function select($conditions = [], $columns = '*', $tableName = null) {
        $table = ($tableName) ? $tableName : $this->table;
        $sql = "SELECT $columns FROM $table";
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', array_map(function ($key) {
                return "$key = :$key";
            }, array_keys($conditions)));
        }
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($conditions);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Select query failed: " . $e->getMessage());
        }
    }

    // Get a row by ID
    public function selectById($id, $tableName = null) {
        $table = ($tableName) ? $tableName : $this->table;
        $sql = "SELECT * FROM  $table WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Select By Id query failed: " . $e->getMessage());
        }
    }

    /**
     * Inserts data into a specified table.
     *
     * @param array $data An associative array of column names and values to insert.
     * @param string $table (Optional) The name of the table to insert data into.
     * @return bool Returns true on success or false on failure.
     *
     * Usage:
     * $data = [
     *     'column1' => 'value1',
     *     'column2' => 'value2',
     *     // ...
     * ];
     * $result = $this->insert($data);
     */
    public function insert($data, $tableName = null) {
        $table = ($tableName) ? $tableName : $this->table;
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            die("Insert query failed: " . $e->getMessage());
        }
    }

    /**
     * Updates records in the specified table with the given data based on the provided conditions.
     *
     * @param array $data An associative array of column-value pairs to update.
     * @param array $conditions An associative array of column-value pairs to use as conditions for the update.
     * @param string $table (Optional) The name of the table to update.
     * @return bool Returns true on success or false on failure.
     *
     * Usage:
     * $table = 'users';
     * $data = ['name' => 'John Doe', 'email' => 'john.doe@example.com'];
     * $conditions = ['id' => 1];
     * $result = $this->update($data, $conditions);
     */
    public function update($data, $conditions, $tableName = null) {
        $table = ($tableName) ? $tableName : $this->table;
        $set = implode(', ', array_map(function ($key) {
            return "$key = :$key";
        }, array_keys($data)));
        $where = implode(' AND ', array_map(function ($key) {
            return "$key = :$key";
        }, array_keys($conditions)));
        $sql = "UPDATE $table SET $set WHERE $where";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(array_merge($data, $conditions));
        } catch (PDOException $e) {
            die("Update query failed: " . $e->getMessage());
        }
    }

    /**
     * Deletes records from the specified table based on the provided conditions.
     *
     * @param string $table The name of the table to delete from.
     * @param array $conditions An associative array of column-value pairs to use as conditions for the delete.
     * @return bool Returns true on success or false on failure.
     *
     * Usage:
     * $table = 'users';
     * $conditions = ['id' => 1];
     * $result = $this->delete($table, $conditions);
     */
    public function delete($conditions, $tableName = null) {
        $table = ($tableName) ? $tableName : $this->table;
        $where = implode(' AND ', array_map(function ($key) {
            return "$key = :$key";
        }, array_keys($conditions)));
        $sql = "DELETE FROM $table WHERE $where";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($conditions);
        } catch (PDOException $e) {
            die("Delete query failed: " . $e->getMessage());
        }
    }
}
