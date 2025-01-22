<?php

namespace App\Libraries;

class Validation {
    public static function validate($data, $rules) {
        $errors = [];
        foreach ($rules as $field => $rule) {
            if (!isset($data[$field])) {
                $errors[$field] = "$field is required";
            } elseif ($rule === 'email' && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = "$field must be a valid email address";
            }
        }
        return $errors;
    }
}
