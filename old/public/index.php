<?php
define('BASEPATH', dirname(dirname(__FILE__)));

// Load configurations
require_once BASEPATH . '/config/constants.php';
require_once BASEPATH . '/config/commons.php';
require_once BASEPATH . '/config/database.php';

// Load libraries
require_once BASEPATH . '/lib/Auth.php';
require_once BASEPATH . '/lib/Validation.php';
require_once BASEPATH . '/lib/Utils.php';

// Load routing helper functions for APIS
require_once BASEPATH . '/helpers/routeHandler.php';

// Load common functions for APIs
require_once BASEPATH . '/api/common.php';

// Simple Router Logic for APIs
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Parse the requested endpoint
$parsedUrl = parse_url($requestUri);
$path = $parsedUrl['path'];

// Ensure the path starts with "/api/v1"
$apiPrefix = "/api/" . LIVE_API_VERSION;

// Ensure the api code available in "/api/v1" folder
$apiDirPath = BASEPATH . "/api/" . LIVE_API_VERSION;

// Ensure the path starts with the base path
if (strpos($requestUri, $apiPrefix) === 0) {
    // Extract group and endpoint
    $endpoint = substr($requestUri, strlen($apiPrefix));
    $segments = explode('/', trim($endpoint, '/'));
    $group = strtolower($segments[0]) ?? null; // First part of the endpoint

    if ($group && file_exists($apiDirPath . "/$group/routes.php")) {
        // Load the group's routes
        require_once $apiDirPath . "/$group/routes.php";

        // Match the request
        $handler = matchRoute($requestMethod, $endpoint);
        if ($handler) {
            call_user_func($handler); // Call the matched handler
        } else {
            jsonResponse('error', 'Route not found.');
        }
    } else {
        jsonResponse('error', "Invalid request " . $requestUri);
    }
} else {
    jsonResponse('error', 'Invalid API route. Must start with "' . $apiPrefix . '".');
}
