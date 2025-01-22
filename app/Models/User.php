<?php

namespace App\Models;

use System\Core\BaseModel;

class User extends BaseModel {
    public function getAllUsers() {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }
}
