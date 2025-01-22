<?php
// Store config globally
$config = [];

function config($key) {
    global $config; // Ensure the global config array is used
    return $config[$key] ?? null; // Use the null coalescing operator
}
