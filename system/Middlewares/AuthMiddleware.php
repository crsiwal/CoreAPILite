<?php
namespace System\Middlewares;

use App\Models\User;
use System\Libraries\Auth;
use System\Libraries\Request;

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
            // All error handling is now done in Auth class
            return false;
        }
    }
}
