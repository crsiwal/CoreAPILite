<?php
namespace System\Libraries;

class Request
{
    private static $user;

    public static function setUser($user)
    {
        self::$user = $user;
    }

    public static function getUser()
    {
        return self::$user;
    }

    /**
     * Get POST request data
     *
     * @return array|string|null The POST data from the request or a specific key value
     */
    public static function post($key = null)
    {
        $data = json_decode(file_get_contents('php://input'), true) ?: [];
        if (count($data) == 0) {
            $data = $_POST;
        }
        return $key ? ($data[$key] ?? null) : $data;
    }

    /**
     * Get GET request data
     *
     * @return array|string|null The GET data from the request (usually from query string) or a specific key value
     */
    public static function get($key = null, $default = null)
    {
        return $key ? ($_GET[$key] ?? $default) : $_GET;
    }

    /**
     * Get PATCH request data
     *
     * @return array|string|null The PATCH data from the request or a specific key value
     */
    public static function patch($key = null)
    {
        $data = json_decode(file_get_contents('php://input'), true) ?: [];
        return $key ? ($data[$key] ?? null) : $data;
    }

    /**
     * Get DELETE request data
     *
     * @return array|string|null The DELETE data from the request or a specific key value
     */
    public static function delete($key = null)
    {
        $data = json_decode(file_get_contents('php://input'), true) ?: [];
        return $key ? ($data[$key] ?? null) : $data;
    }

    /**
     * Get PUT request data
     *
     * @return array|string|null The PUT data from the request or a specific key value
     */
    public static function put($key = null)
    {
        $data = json_decode(file_get_contents('php://input'), true) ?: [];
        return $key ? ($data[$key] ?? null) : $data;
    }

    /**
     * Get request headers
     *
     * @return array|string|null The headers from the request or a specific key value
     */
    public static function header($key = null)
    {
        $headers = getallheaders();
        return $key ? ($headers[$key] ?? null) : $headers;
    }

    /**
     * Get Bearer token from Authorization header
     *
     * @return string|null The Bearer token from the Authorization header
     */
    public static function bearerToken()
    {
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            $authHeader    = $headers['Authorization'];
            $authHeaderArr = explode(" ", $authHeader);
            if (count($authHeaderArr) == 2) {
                return $authHeaderArr[1];
            }
        }
        return null;
    }

    public static function getBaseUrl()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host     = $_SERVER['HTTP_HOST'];
        return $protocol . '://' . $host;
    }
}
