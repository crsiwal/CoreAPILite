<?php
namespace System\Libraries;

class JWT
{
    private static $secretKey;
    private static $algorithm = 'HS256';
    private static $expiration = 3600; // 1 hour

    public static function setSecretKey($key)
    {
        self::$secretKey = $key;
    }

    public static function setExpiration($seconds)
    {
        self::$expiration = $seconds;
    }

    public static function generate($payload)
    {
        $header = [
            'alg' => self::$algorithm,
            'typ' => 'JWT'
        ];

        $payload['iat'] = time();
        $payload['exp'] = time() + self::$expiration;

        $base64UrlHeader = self::base64UrlEncode(json_encode($header));
        $base64UrlPayload = self::base64UrlEncode(json_encode($payload));

        $signature = self::sign($base64UrlHeader . '.' . $base64UrlPayload);
        $base64UrlSignature = self::base64UrlEncode($signature);

        return $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;
    }

    public static function validate($token)
    {
        if (!$token) {
            return false;
        }

        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = $parts;

        $signature = self::base64UrlDecode($base64UrlSignature);
        $expectedSignature = self::sign($base64UrlHeader . '.' . $base64UrlPayload);

        if (!hash_equals($signature, $expectedSignature)) {
            return false;
        }

        $payload = json_decode(self::base64UrlDecode($base64UrlPayload), true);

        if (!$payload) {
            return false;
        }

        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return false;
        }

        return $payload;
    }

    private static function sign($data)
    {
        return hash_hmac('sha256', $data, self::$secretKey, true);
    }

    private static function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
} 