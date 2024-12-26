<?php

class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->addRoute('GET', $path, $callback);
    }

    public function post($path, $callback) {
        $this->addRoute('POST', $path, $callback);
    }

    private function addRoute($method, $path, $callback) {
        $this->routes[] = ['method' => $method, 'path' => $path, 'callback' => $callback];
    }

    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $currentPath = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $baseDir = trim(PROJECT_DIR, '/');
        $currentRoute = $currentPath === $baseDir ? '' : str_replace($baseDir . '/', '', $currentPath);

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && trim($route['path'], '/') === $currentRoute) {
                call_user_func($route['callback']);
                exit;
            }
        }

        // Default 404 handler
        http_response_code(404);
        echo "<div class='alert alert-danger text-center'>Page not found!</div>";
    }
}
