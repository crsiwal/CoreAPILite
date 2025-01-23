<?php

namespace System\Libraries;

class Session {
    // Start the session if not already started
    public static function start() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Set a session value
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }

    // Get a session value
    public static function get($key) {
        self::start();
        return $_SESSION[$key] ?? null;
    }

    // Check if a session key exists
    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }

    // Remove a session key
    public static function remove($key) {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    // Destroy the entire session
    public static function destroy() {
        if (session_status() != PHP_SESSION_NONE) {
            session_unset();
            session_destroy();
        }
    }

    // Set a flash message (one-time session data)
    public static function flash($key, $value) {
        self::start();
        $_SESSION['flash'][$key] = $value;
    }

    // Get and remove a flash message
    public static function getFlash($key) {
        self::start();
        if (isset($_SESSION['flash'][$key])) {
            $value = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $value;
        }
        return null;
    }

    // Regenerate session ID for better security
    public static function regenerate($deleteOldSession = true) {
        if (session_status() != PHP_SESSION_NONE) {
            session_regenerate_id($deleteOldSession);
        }
    }
}
