<?php
// Utility functions
class Utils {
    public static function generateUUID() {
        return bin2hex(random_bytes(16));
    }
}
?>
