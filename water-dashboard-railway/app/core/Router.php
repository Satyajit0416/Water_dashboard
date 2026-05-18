<?php
// ============================================================
// app/core/Router.php - URL Router
// ============================================================

class Router {
    private $routes = [];
    private $url;

    public function __construct() {
        $this->url = $this->parseUrl();
    }

    // Parse URL into parts
    private function parseUrl() {
        if (isset($_GET['url'])) {
            return filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);
        }
        return '';
    }

    // Route the request to controller/method
    public function route() {
        $urlParts = $this->url ? explode('/', $this->url) : [];

        // Default controller and method
        $controllerName = isset($urlParts[0]) && !empty($urlParts[0]) 
            ? ucfirst(strtolower($urlParts[0])) . 'Controller' 
            : 'HomeController';
        
        $methodName = isset($urlParts[1]) && !empty($urlParts[1]) 
            ? strtolower($urlParts[1]) 
            : 'index';
        
        $params = array_slice($urlParts, 2);

        // Load controller file
        $controllerFile = BASE_PATH . '/app/controllers/' . $controllerName . '.php';

        if (!file_exists($controllerFile)) {
            $this->notFound();
            return;
        }

        require_once $controllerFile;

        if (!class_exists($controllerName)) {
            $this->notFound();
            return;
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $methodName)) {
            $this->notFound();
            return;
        }

        // Call controller method with params
        call_user_func_array([$controller, $methodName], $params);
    }

    // 404 Not Found
    private function notFound() {
        http_response_code(404);
        $viewFile = BASE_PATH . '/app/views/layouts/404.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            echo '<h1>404 - Page Not Found</h1>';
        }
    }
}
