<?php
namespace System\Libraries;

use App\Configs\ResponseCode;
use System\Libraries\Response;

class Auth
{
    private static $jwt;
    private static $userModel;

    public static function init($userModel)
    {
        self::$userModel = $userModel;
        self::$jwt       = new JWT();

        // Set JWT secret key from config
        self::$jwt->setSecretKey(getenv('JWT_SECRET_KEY') ?: 'your-secret-key-here');
        // Set token expiration to 24 hours
        self::$jwt->setExpiration(86400);
    }

    public static function generateToken($userId, $role = 'user')
    {
        return self::$jwt->generate([
            'user_id' => $userId,
            'role'    => $role,
        ]);
    }

    public static function validateToken($token)
    {
        if (! $token) {
            self::handleError('Authorization token is required', ResponseCode::UNAUTHORIZED);
        }

        $payload = self::$jwt->validate($token);
        if (! $payload) {
            self::handleError('Invalid or expired token', ResponseCode::INVALID_TOKEN);
        }

        return $payload;
    }

    public static function getCurrentUser()
    {
        $token   = Request::bearerToken();
        $payload = self::validateToken($token);

        $user = self::$userModel->getById($payload['user_id']);
        if (! $user) {
            self::handleError('User not found', ResponseCode::USER_NOT_FOUND);
        }

        return $user;
    }

    public static function isAuthenticated()
    {
        try {
            $token = Request::bearerToken();
            return (bool) self::validateToken($token);
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function hasRole($role)
    {
        $token   = Request::bearerToken();
        $payload = self::validateToken($token);
        return $payload['role'] === $role;
    }

    private static function handleError($message, $code)
    {
        Response::error([
            'code'    => $code,
            'message' => $message,
        ], $code);
    }
}
