<?php
namespace System\Middlewares;

use App\Configs\ResponseCode;
use App\Models\User;
use System\Libraries\Auth;
use System\Libraries\Request;
use System\Libraries\Response;

class AuthMiddleware
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
        Auth::init($this->userModel);
    }

    public function handle($method, $uri)
    {
        try {
            // Validate token and get user
            $user = Auth::getCurrentUser();

            // Store user in request for later use
            Request::setUser($user);

            // Continue to next middleware/controller
            return true;
        } catch (\Exception $e) {
            // Handle specific error cases
            switch ($e->getMessage()) {
                case 'Authorization token is required':
                    Response::error([
                        'code'    => ResponseCode::UNAUTHORIZED,
                        'message' => 'Authorization token is required',
                    ]);
                    break;
                case 'Invalid or expired token':
                    Response::error([
                        'code'    => ResponseCode::INVALID_TOKEN,
                        'message' => 'Invalid or expired token',
                    ]);
                    break;
                case 'User not found':
                    Response::error([
                        'code'    => ResponseCode::USER_NOT_FOUND,
                        'message' => 'User not found',
                    ]);
                    break;
                default:
                    Response::error([
                        'code'    => ResponseCode::UNAUTHORIZED,
                        'message' => 'Authentication failed',
                    ]);
            }
            return false;
        }
    }
}
