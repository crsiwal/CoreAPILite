<?php

namespace App\Models;

use System\Core\Model;

class UserModel extends Model {
    public function __construct() {
        parent::__construct("users");
    }
}
