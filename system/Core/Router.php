<?php

namespace System\Core;

use App\Configs\Constants;
use System\Libraries\Logger;
use System\Libraries\Response;

class Router {
    private $routes = [];
    private $groupPrefix = '';
    private $groupMiddleware = [];

    public function addRoute($method, $path, $handler, $middleware = []) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $this->normalizePath($this->groupPrefix . $path),
            'handler' => $handler,
            'middleware' => array_merge($this->groupMiddleware, $middleware)
        ];
    }

    public function get($path, $handler, $middleware = []) {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function post($path, $handler, $middleware = []) {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    public function put($path, $handler, $middleware = []) {
        $this->addRoute('PUT', $path, $handler, $middleware);
    }

    public function delete($path, $handler, $middleware = []) {
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }

    public function group($prefix, $callback, $middleware = []) {
        $previousGroupPrefix = $this->groupPrefix;
        $previousGroupMiddleware = $this->groupMiddleware;

        $this->groupPrefix = $this->normalizePath($previousGroupPrefix . $prefix);
        $this->groupMiddleware = array_merge($previousGroupMiddleware, $middleware);

        call_user_func($callback, $this);

        $this->groupPrefix = $previousGroupPrefix;
        $this->groupMiddleware = $previousGroupMiddleware;
    }

    public function dispatch() {
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        $uri = $this->normalizePath($_SERVER['REQUEST_URI']);

        // Extract query parameters from the URL
        $queryParams = [];
        if (strpos($uri, '?') !== false) {
            $uriParts = explode('?', $uri);
            $uri = $uriParts[0]; // Update URI to match the path only
            parse_str($uriParts[1], $queryParams); // Parse the query string
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->match($route['path'], $uri, $params)) {
                // Merge query parameters with route parameters
                $params = array_merge($params, $queryParams);
                foreach ($route['middleware'] as $middleware) {
                    if (!$this->callMiddleware($middleware, $method, $uri)) {
                        return;
                    }
                }

                if (is_callable($route['handler'])) {
                    return call_user_func_array($route['handler'], $params);
                } elseif (is_string($route['handler']) && strpos($route['handler'], '@') !== false) {
                    return $this->callController($route['handler'], $params);
                }
            }
        }

        Logger::getInstance()->error("404 Not Found: $method $uri");
        Response::json(["error" => "404 Not Found"], Response::HTTP_NOT_FOUND);
    }

    private function normalizePath($path) {
        return rtrim($path, '/') ?: '/';
    }


    private function match($routePath, $requestPath, &$params) {
        $routeParts = explode('/', $routePath);
        $requestParts = explode('/', $requestPath);

        // Match the number of parts in the URL
        if (count($routeParts) !== count($requestParts)) {
            return false;
        }

        $params = [];
        foreach ($routeParts as $index => $part) {
            // Handle dynamic parameters like {id}
            if (strpos($part, '{') === 0 && strpos($part, '}') === strlen($part) - 1) {
                $params[] = $requestParts[$index]; // capture dynamic part
            } elseif ($part !== $requestParts[$index]) {
                return false; // exact match
            }
        }

        return true;
    }


    private function callController($handler, $params) {
        [$controller, $method] = explode('@', $handler);
        $controllerClass = Constants::APP_DIR_NAME . "\\" . Constants::CONTROLLERS_DIR_NAME . "\\" . $controller;
        if (!class_exists($controllerClass)) {
            Logger::getInstance()->error("Controller not found: $controller");
            Response::json(["error" => "Controller not found: $controller"], Response::HTTP_INTERNAL_SERVER_ERROR);
            return;
        }

        $controllerInstance = new $controllerClass();

        if (!method_exists($controllerInstance, $method)) {
            Logger::getInstance()->error("Method not found in controller: $method");
            Response::json(["error" => "Method not found in controller: $method"], Response::HTTP_INTERNAL_SERVER_ERROR);
            return;
        }

        return call_user_func_array([$controllerInstance, $method], $params);
    }

    private function callMiddleware($middleware, $method, $uri) {
        if (is_callable($middleware)) {
            return call_user_func($middleware, $method, $uri);
        } elseif (is_string($middleware) && class_exists($middleware)) {
            $middlewareInstance = new $middleware();
            if (method_exists($middlewareInstance, 'handle')) {
                return call_user_func([$middlewareInstance, 'handle'], $method, $uri);
            }
        }
        Logger::getInstance()->error("Invalid middleware: $middleware");
        Response::json(["error" => "Invalid middleware: $middleware"], Response::HTTP_INTERNAL_SERVER_ERROR);
        return false;
    }
}
