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
            Response::error([
                'code'    => ResponseCode::UNAUTHORIZED,
                'message' => 'Authorization token is required',
            ]);
        }

        // Remove 'Bearer ' prefix if present
        $token = str_replace('Bearer ', '', $token);

        $payload = self::$jwt->validate($token);
        if (! $payload) {
            Response::error([
                'code'    => ResponseCode::INVALID_TOKEN,
                'message' => 'Invalid or expired token',
            ]);
        }

        return $payload;
    }

    public static function getCurrentUser()
    {
        $token   = Request::header('Authorization');
        $payload = self::validateToken($token);

        $user = self::$userModel->getById($payload['user_id']);
        if (! $user) {
            Response::error([
                'code'    => ResponseCode::USER_NOT_FOUND,
                'message' => 'User not found',
            ]);
        }

        return $user;
    }

    public static function isAuthenticated()
    {
        try {
            $token = Request::header('Authorization');
            return (bool) self::validateToken($token);
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function hasRole($role)
    {
        $payload = self::validateToken(Request::header('Authorization'));
        return $payload['role'] === $role;
    }
}
