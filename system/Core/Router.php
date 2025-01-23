<?php

namespace System\Core;

class Router {

    protected $routes = [];
    protected $groupPrefix = '';  // Store the prefix for route groups

    // Method to define a route group
    public function group($prefix) {
        // Ensure the prefix starts with a "/"
        if (strpos($prefix, '/') !== 0) {
            $prefix = '/' . $prefix;
        }

        $this->groupPrefix = $prefix;
        return $this;  // Return Router instance for chaining
    }

    private function getRouteRegex($route) {
        // Replace placeholders with stricter regex patterns based on types
        return preg_replace_callback('/\{([a-zA-Z0-9_]+):([a-z]+)\}/', function ($matches) {
            $paramName = $matches[1];
            $paramType = $matches[2];

            // Define regex for supported types
            switch ($paramType) {
                case 'num':    // Numbers only
                    return '(?P<' . $paramName . '>\d+)';
                case 'string': // Strings only (alphanumeric)
                    return '(?P<' . $paramName . '>[a-zA-Z0-9]+)';
                default:       // Fallback for unsupported types
                    throw new \Exception("Unsupported parameter type: $paramType");
            }
        }, $route);
    }

    // Method for handling GET routes
    public function get($route, $controller, $methodName) {
        $filteredRoute = $this->getRouteRegex($route);
        // Add the route to the list
        $this->add('GET', $filteredRoute, $controller, $methodName);
        return $this;  // Return Router instance for chaining
    }

    // Method for handling POST routes
    public function post($route, $controller, $methodName) {
        $filteredRoute = $this->getRouteRegex($route);
        // Add the route to the list
        $this->add('POST', $filteredRoute, $controller, $methodName);
        return $this;  // Return Router instance for chaining
    }

    // Method for handling PUT routes
    public function put($route, $controller, $methodName) {
        $filteredRoute = $this->getRouteRegex($route);
        // Add the route to the list        
        $this->add('PUT', $filteredRoute, $controller, $methodName);
        return $this;  // Return Router instance for chaining
    }

    // Method for handling PATCH routes
    public function patch($route, $controller, $methodName) {
        $filteredRoute = $this->getRouteRegex($route);
        // Add the route to the list        
        $this->add('PATCH', $filteredRoute, $controller, $methodName);
        return $this;  // Return Router instance for chaining
    }

    // Method for handling DELETE routes
    public function delete($route, $controller, $methodName) {
        $filteredRoute = $this->getRouteRegex($route);
        // Add the route to the list        
        $this->add('DELETE', $filteredRoute, $controller, $methodName);
        return $this;  // Return Router instance for chaining
    }

    // Add a route to the route list
    private function add($method, $route, $controller, $methodName) {
        // If a group prefix exists, prepend it to the route
        if ($this->groupPrefix) {
            $route = $this->groupPrefix . $route;
        }
        // Add the route to the list
        $this->routes[] = [
            'method' => $method,
            'route' => $route,
            'controller' => $controller,
            'methodName' => $methodName,
        ];
    }

    public function dispatch($requestUri, $requestMethod) {
        foreach ($this->routes as $route) {
            // Convert the route into a valid regex pattern
            $pattern = "#^" . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[^/]+)', $route['route']) . "/?$#";

            // Match the request URI against the pattern
            if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $params)) {
                $controller = "App\\Controllers\\" . $route['controller'];
                if (class_exists($controller)) {
                    $controllerInstance = new $controller();
                    if (method_exists($controllerInstance, $route['methodName'])) {
                        // Extract named parameters from $params
                        $params = array_filter($params, 'is_string', ARRAY_FILTER_USE_KEY);
                        return $controllerInstance->{$route['methodName']}(...array_values($params));
                    } else {
                        http_response_code(500);
                        echo json_encode(['error' => 'Method not found']);
                        return;
                    }
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => 'Controller not found']);
                    return;
                }
            }
        }

        // If no routes match, return 404
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
    }
}
