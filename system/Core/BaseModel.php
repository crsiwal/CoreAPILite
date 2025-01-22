<?php

namespace System\Core;

use PDO;

class BaseModel {
    protected $db;

    public function __construct() {
        $config = include __DIR__ . '/../../Configs/database.php';
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']}";
        $this->db = new PDO($dsn, $config['username'], $config['password']);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
