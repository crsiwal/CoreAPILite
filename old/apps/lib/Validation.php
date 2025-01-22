<?php
// Validation library
class Validation {
    public static function isEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
?>
