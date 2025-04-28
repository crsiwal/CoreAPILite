<?php
namespace System\Libraries;

class Response
{
    // Common HTTP Status Codes
    const HTTP_OK                    = 200;
    const HTTP_CREATED               = 201;
    const HTTP_NO_CONTENT            = 204;
    const HTTP_BAD_REQUEST           = 400;
    const HTTP_UNAUTHORIZED          = 401;
    const HTTP_FORBIDDEN             = 403;
    const HTTP_NOT_FOUND             = 404;
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    /**
     * Send a JSON response
     *
     * @param mixed $data
     * @param int $statusCode
     */
    public static function json($data, $statusCode = self::HTTP_OK)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    /**
     * Send a plain text response
     *
     * @param string $message
     * @param int $statusCode
     */
    public static function text($message, $statusCode = self::HTTP_OK)
    {
        header('Content-Type: text/plain');
        http_response_code($statusCode);
        echo $message;
        exit;
    }

    /**
     * Send an HTML response
     *
     * @param string $html
     * @param int $statusCode
     */
    public static function html($html, $statusCode = self::HTTP_OK)
    {
        header('Content-Type: text/html');
        http_response_code($statusCode);
        echo $html;
        exit;
    }

    /**
     * Sends a JSON response with an success status and the provided data.
     *
     * @param mixed $data The data to be included in the response.
     * @param int $statusCode The HTTP status code for the response. Defaults to HTTP_OK.
     *
     * @return void
     */
    public static function success($data, $statusCode = self::HTTP_OK)
    {
        self::json(
            [
                'status' => "success",
                'data'   => $data,
            ],
            $statusCode
        );
    }

    /**
     * Sends a JSON response with an error status and the provided data.
     *
     * @param mixed $data The data to be included in the response.
     * @param int $statusCode The HTTP status code for the response. Defaults to HTTP_BAD_REQUEST.
     *
     * @return void
     */
    public static function error($data, $statusCode = self::HTTP_BAD_REQUEST)
    {
        self::json([
            'status' => "error",
            'data'   => $data,
        ], $statusCode);
    }
}
