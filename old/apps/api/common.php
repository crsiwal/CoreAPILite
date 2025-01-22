<?php
// Common functions for APIs
function jsonResponse($status, $data) {

    // Define the status code mappings
    $statusCodes = [
        'success' => 200,
        'created' => 201,
        'no_content' => 204,
        'bad_request' => 400,
        'unauthorized' => 401,
        'forbidden' => 403,
        'not_found' => 404,
        'method_not_allowed' => 405,
        'conflict' => 409,
        'internal_server_error' => 500,
        'service_unavailable' => 503,
    ];

    // Default to 400 (Bad Request) if the status is not found in the array
    $statusCode = isset($statusCodes[$status]) ? $statusCodes[$status] : 400;

    // Set the HTTP response code
    http_response_code($statusCode);

    // Return the response as JSON
    echo json_encode(['status' => $status, 'data' => $data]);
    die();
}
