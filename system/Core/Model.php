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

use App\Configs\Constants;
use PDO;
use PDOException;
use System\Libraries\Logger;

class Model
{
    protected $calledFrom;
    protected $table; // Table name to be set by child model
    protected $db;

    /**
     * Constructor
     *
     * Creates a new PDO connection to the database.
     *
     * @param string $table (Optional) The name of the table to use for the model.
     */
    public function __construct($table = null, $calledFrom = null)
    {
        try {
            $config   = include Constants::CONFIGS_DIR_PATH . 'database.php';
            $dsn      = "mysql:host={$config['host']};dbname={$config['dbname']}";
            $this->db = new PDO($dsn, $config['username'], $config['password']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->table      = $table;
            $this->calledFrom = $calledFrom;
            return $this->db;
        } catch (PDOException $e) {
            Logger::getInstance()->error("Connection failed: " . $e->getMessage() . " [Called from: " . ($this->calledFrom ?? 'unknown') . "] [DSN: " . $dsn . "]");
            die("Connection failed: " . $e->getMessage());
        }
    }

    /**
     * Executes a raw SQL query with parameters
     *
     * @param string $sql The SQL query to execute
     * @param array $params (Optional) Parameters to bind to the query
     * @param bool $fetchAll (Optional) Whether to fetch all results or just one
     * @return array|false The query results or false on failure
     */
    public function query($sql, $params = [], $fetchAll = false)
    {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            if ($fetchAll) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::getInstance()->error("Query failed: " . $e->getMessage() . " [Called from: " . ($this->calledFrom ?? 'unknown') . "] [Query: " . $sql . "] [Params: " . json_encode($params) . "]");
            return false;
        }
    }

    /**
     * Sets the table name for the model.
     *
     * @param string $table The name of the table to set.
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * Selects records from a specified table with optional conditions and columns.
     *
     * @param array $conditionsAnd (Optional) An associative array of conditions where the key is the column name and the value is the value to match.
     *                             If a value is an array, it will use an IN condition.
     * @param string|array $columns (Optional) The columns to select, either as a comma-separated string or an array of column names. Defaults to '*'.
     * @param string $tableName (Optional) The name of the table to select from.
     * @param bool $singleRecord (Optional) Whether to fetch a single record or multiple. Defaults to false.
     * @param int $limit (Optional) Maximum number of records to return.
     * @param int $offset (Optional) Number of records to skip.
     * @param string $orderBy (Optional) Column to order by.
     * @param string $orderDirection (Optional) Order direction ('ASC' or 'DESC').
     * @return array|false An array of associative arrays representing the selected records, or false on failure.
     *
     * @example
     * $result = $this->select(['id' => 1]);
     * $result = $this->select(['status' => 'active'], 'name, email');
     * $result = $this->select(['id' => [1, 2, 3]]); // In condition
     * $result = $this->select([], '*', null, false, 10, 0, 'created_at', 'DESC'); // Pagination
     */
    public function select($conditionsAnd = [], $columns = '*', $tableName = null, $singleRecord = false, $limit = null, $offset = null, $orderBy = null, $orderDirection = 'ASC')
    {
        $table  = ($tableName) ? $tableName : $this->table;
        $sql    = "SELECT $columns FROM $table";
        $params = [];

        if (! empty($conditionsAnd)) {
            $clauses = [];

            foreach ($conditionsAnd as $key => $value) {
                if (is_array($value)) {
                    // Handle IN condition
                    $placeholders = [];
                    foreach ($value as $index => $val) {
                        $ph             = ":{$key}_{$index}";
                        $placeholders[] = $ph;
                        $params[$ph]    = $val;
                    }
                    $clauses[] = "$key IN (" . implode(', ', $placeholders) . ")";
                } else {
                    $clauses[]       = "$key = :$key";
                    $params[":$key"] = $value;
                }
            }

            $sql .= " WHERE " . implode(' AND ', $clauses);
        }

        // Add ORDER BY clause if specified
        if ($orderBy) {
            $orderDirection = strtoupper($orderDirection) === 'DESC' ? 'DESC' : 'ASC';
            $sql .= " ORDER BY $orderBy $orderDirection";
        }

        // Add LIMIT and OFFSET if specified
        if ($limit !== null) {
            $sql .= " LIMIT :limit";
            $params[':limit'] = (int) $limit;

            if ($offset !== null) {
                $sql .= " OFFSET :offset";
                $params[':offset'] = (int) $offset;
            }
        }

        try {
            $stmt = $this->db->prepare($sql);

            // Bind parameters with proper types
            foreach ($params as $key => $value) {
                if (strpos($key, ':limit') === 0 || strpos($key, ':offset') === 0) {
                    $stmt->bindValue($key, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $value);
                }
            }

            $stmt->execute();

            if ($singleRecord) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            Logger::getInstance()->error("Select query failed: " . $e->getMessage() . " [Called from: " . ($this->calledFrom ?? 'unknown') . "] [Query: " . $sql . "] [Params: " . json_encode($params) . "]");
            return false;
        }
    }

    public function selectOne($conditionsAnd = [], $columns = '*', $tableName = null)
    {
        return $this->select($conditionsAnd, $columns, $tableName, true);
    }

    // Get a row by ID
    public function selectById($id, $tableName = null)
    {
        $table = ($tableName) ? $tableName : $this->table;
        $sql   = "SELECT * FROM  $table WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::getInstance()->error("Select By Id query failed: " . $e->getMessage() . " [Called from: " . ($this->calledFrom ?? 'unknown') . "] [Query: " . $sql . "] [ID: " . $id . "]");
            return false;
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
    public function insert($data, $tableName = null)
    {
        $table        = ($tableName) ? $tableName : $this->table;
        $columns      = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql          = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($data);
            // Return the last inserted ID
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            Logger::getInstance()->error("Insert query failed: " . $e->getMessage() . " [Called from: " . ($this->calledFrom ?? 'unknown') . "] [Query: " . $sql . "] [Data: " . json_encode($data) . "]");
            return false;
        }
    }

    /**
     * Inserts multiple rows into a specified table and returns the inserted IDs.
     *
     * @param array $data An array of associative arrays. Each associative array represents a row to insert.
     * @param string $tableName (Optional) The name of the table to insert data into.
     * @return array|false Returns an array of inserted IDs on success or false on failure.
     */
    public function bulkInsert($data, $tableName = null)
    {

        if (empty($data)) {
            return false;
        }

        $table   = ($tableName) ? $tableName : $this->table;
        $columns = implode(', ', array_keys($data[0]));

        $placeholdersArr = [];
        $params          = [];

        foreach ($data as $index => $row) {
            $placeholders = [];
            foreach ($row as $column => $value) {
                $key            = ":{$column}_{$index}";
                $placeholders[] = $key;
                $params[$key]   = $value;
            }
            $placeholdersArr[] = '(' . implode(', ', $placeholders) . ')';
        }

        $placeholdersSql = implode(", ", $placeholdersArr);
        $sql             = "INSERT INTO $table ($columns) VALUES $placeholdersSql";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            $firstId       = (int) $this->db->lastInsertId();
            $insertedCount = count($data);

            // Build the array of inserted IDs
            $ids = [];
            for ($i = 0; $i < $insertedCount; $i++) {
                $ids[] = $firstId + $i;
            }

            return $ids;
        } catch (PDOException $e) {
            Logger::getInstance()->error("Bulk insert query failed: " . $e->getMessage() . " [Called from: " . ($this->calledFrom ?? 'unknown') . "] [Query: " . $sql . "] [Data: " . json_encode($data) . "]");
            return false;
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
    public function update($data, $conditions, $tableName = null)
    {
        $table = ($tableName) ? $tableName : $this->table;
        $set   = implode(', ', array_map(function ($key) {
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
            Logger::getInstance()->error("Update query failed: " . $e->getMessage() . " [Called from: " . ($this->calledFrom ?? 'unknown') . "] [Query: " . $sql . "] [Data: " . json_encode($data) . "] [Conditions: " . json_encode($conditions) . "]");
            return false;
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
    public function delete($conditions, $tableName = null)
    {
        if (empty($conditions)) {
            return false;
        }

        $table = ($tableName) ? $tableName : $this->table;
        $where = implode(' AND ', array_map(function ($key) {
            return "$key = :$key";
        }, array_keys($conditions)));
        $sql = "DELETE FROM $table WHERE $where";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($conditions);
        } catch (PDOException $e) {
            Logger::getInstance()->error("Delete query failed: " . $e->getMessage() . " [Called from: " . ($this->calledFrom ?? 'unknown') . "] [Query: " . $sql . "] [Conditions: " . json_encode($conditions) . "]");
            return false;
        }
    }

    /**
     * Counts the number of records in the specified table based on the provided conditions.
     *
     * @param array $conditions (Optional) An associative array of conditions where the key is the column name and the value is the value to match.
     * @param string $tableName (Optional) The name of the table to count.
     * @return int|false Returns the count of records on success or false on failure.
     */
    public function count($conditions = [], $tableName = null)
    {

        $table = ($tableName) ? $tableName : $this->table;
        $sql   = "SELECT COUNT(*) FROM $table";
        if (! empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', array_map(function ($key) {
                return "$key = :$key";
            }, array_keys($conditions)));
        }

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($conditions);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logger::getInstance()->error("Count query failed: " . $e->getMessage() . " [Called from: " . ($this->calledFrom ?? 'unknown') . "] [Query: " . $sql . "] [Conditions: " . json_encode($conditions) . "]");
            return false;
        }
    }
}
