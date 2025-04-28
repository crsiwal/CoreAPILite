<?php
namespace App\Configs;

class ResponseCode
{
    // Success codes
    const SUCCESS = 200;
    const CREATED = 201;
    const UPDATED = 200;
    const DELETED = 200;

    // Error codes
    const INVALID_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const CONFLICT = 409;
    const TOO_MANY_REQUESTS = 429;
    const INTERNAL_SERVER_ERROR = 500;

    // Authentication specific codes
    const INVALID_CREDENTIALS = 401;
    const INVALID_TOKEN = 401;
    const TOKEN_EXPIRED = 401;
    const EMAIL_ALREADY_EXISTS = 409;
    const USER_NOT_FOUND = 404;
    const INVALID_OTP = 400;

    // Database specific codes
    const MYSQL_QUERY_FAILED = 500;
    const DUPLICATE_ENTRY = 409;
}
