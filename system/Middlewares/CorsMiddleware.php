<?php
namespace System\Middlewares;

class CorsMiddleware
{
    private $allowedOrigins;
    private $allowedMethods;
    private $allowedHeaders;
    private $exposedHeaders;
    private $maxAge;
    private $allowCredentials;

    public function __construct(array $config = [])
    {
        // Default configuration
        $this->allowedOrigins = $config['allowed_origins'] ?? [
            'http://localhost:5173',  // Vite default port
            'http://localhost:3000',  // React default port
            'http://localhost:8080',  // Common dev port
            'https://web3.adgyde.in', // Production domain
        ];
        $this->allowedMethods = $config['allowed_methods'] ?? ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH'];
        $this->allowedHeaders = $config['allowed_headers'] ?? [
            'Content-Type',
            'Authorization',
            'X-Requested-With',
            'Accept',
            'Origin',
            'Access-Control-Request-Method',
            'Access-Control-Request-Headers',
        ];
        $this->exposedHeaders   = $config['exposed_headers'] ?? ['Content-Length', 'Content-Range'];
        $this->maxAge           = $config['max_age'] ?? 86400; // 24 hours
        $this->allowCredentials = $config['allow_credentials'] ?? true;
    }

    public function handle($method, $uri)
    {
        // Get the origin from the request headers
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

        // Handle preflight requests
        if ($method === 'OPTIONS') {
            $this->handlePreflight($origin);
            return false;
        }

        // Set CORS headers for actual requests
        $this->setCorsHeaders($origin);

        return true;
    }

    private function handlePreflight($origin)
    {
        // Check if the origin is allowed
        if (! $this->isOriginAllowed($origin)) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode([
                'status'  => 'error',
                'message' => 'Origin not allowed',
                'origin'  => $origin,
            ]);
            exit;
        }

        // Set preflight headers
        $this->setCorsHeaders($origin);
        http_response_code(204);
        exit;
    }

    private function setCorsHeaders($origin)
    {
        // Set the Access-Control-Allow-Origin header
        if (in_array('*', $this->allowedOrigins)) {
            header('Access-Control-Allow-Origin: *');
        } elseif (in_array($origin, $this->allowedOrigins)) {
            header('Access-Control-Allow-Origin: ' . $origin);
        }

        // Set other CORS headers
        if (! empty($this->allowedMethods)) {
            header('Access-Control-Allow-Methods: ' . implode(', ', $this->allowedMethods));
        }

        if (! empty($this->allowedHeaders)) {
            header('Access-Control-Allow-Headers: ' . implode(', ', $this->allowedHeaders));
        }

        if (! empty($this->exposedHeaders)) {
            header('Access-Control-Expose-Headers: ' . implode(', ', $this->exposedHeaders));
        }

        if ($this->maxAge) {
            header('Access-Control-Max-Age: ' . $this->maxAge);
        }

        if ($this->allowCredentials) {
            header('Access-Control-Allow-Credentials: true');
        }
    }

    private function isOriginAllowed($origin)
    {
        if (empty($origin)) {
            return false;
        }

        if (in_array('*', $this->allowedOrigins)) {
            return true;
        }

        return in_array($origin, $this->allowedOrigins);
    }
}
