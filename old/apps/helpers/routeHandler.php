<?php
// Store the routes in a global array
$GLOBALS['routes'] = [];

// Define HTTP method functions
function group($groupName, $callback) {
    $GLOBALS['currentGroup'] = $groupName; // Set the current group
    $callback(); // Execute the callback to define routes
    unset($GLOBALS['currentGroup']); // Reset the group after definition
}

function get($endpoint, $handler) {
    defineRoute('GET', $endpoint, $handler);
}

function post($endpoint, $handler) {
    defineRoute('POST', $endpoint, $handler);
}

function put($endpoint, $handler) {
    defineRoute('PUT', $endpoint, $handler);
}

function patch($endpoint, $handler) {
    defineRoute('PATCH', $endpoint, $handler);
}

function delete($endpoint, $handler) {
    defineRoute('DELETE', $endpoint, $handler);
}

function defineRoute($method, $endpoint, $handler) {
    $group = $GLOBALS['currentGroup'] ?? ''; // Get the current group
    $fullEndpoint = $group . $endpoint; // Combine group and endpoint
    $GLOBALS['routes'][$method][$fullEndpoint] = $handler; // Store the route
}


// Match route function
function matchRoute($method, $endpoint) {
    foreach ($GLOBALS['routes'][$method] ?? [] as $route => $handler) {
        if (matchEndpoint($route, $endpoint)) {
            return $handler;
        }
    }
    return null;
}

// Match dynamic endpoint helper
function matchEndpoint($route, $endpoint) {
    $routePattern = preg_replace('/{[^}]+}/', '[^/]+', $route);
    return preg_match("#^$routePattern$#", $endpoint);
}
