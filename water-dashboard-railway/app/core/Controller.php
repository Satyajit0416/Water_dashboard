<?php
// ============================================================
// app/core/Controller.php - Base Controller Class
// ============================================================

class Controller {
    
    // Load a model
    protected function model($model) {
        $modelFile = BASE_PATH . '/app/models/' . $model . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        }
        die("Model {$model} not found.");
    }

    // Load a view
    protected function view($view, $data = []) {
        // Extract data array to variables
        extract($data);
        
        $viewFile = BASE_PATH . '/app/views/' . str_replace('.', '/', $view) . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View {$view} not found.");
        }
    }

    // Render view with layout
    protected function render($view, $data = [], $layout = 'main') {
        extract($data);
        
        $content = BASE_PATH . '/app/views/' . str_replace('.', '/', $view) . '.php';
        $layoutFile = BASE_PATH . '/app/views/layouts/' . $layout . '.php';
        
        if (!file_exists($content)) die("View {$view} not found.");
        if (!file_exists($layoutFile)) die("Layout {$layout} not found.");
        
        require_once $layoutFile;
    }

    // Redirect to URL
    protected function redirect($url) {
        header('Location: ' . APP_URL . '/' . ltrim($url, '/'));
        exit();
    }

    // Check if user is authenticated
    protected function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
        }
    }

    // Check if user is admin
    protected function requireAdmin() {
        $this->requireAuth();
        if ($_SESSION['user_role'] !== 'admin') {
            $this->redirect('dashboard');
        }
    }

    // Check if user is farmer
    protected function requireFarmer() {
        $this->requireAuth();
        if ($_SESSION['user_role'] !== 'farmer') {
            $this->redirect('admin/dashboard');
        }
    }

    // Get POST data safely
    protected function getPost($key = null, $default = '') {
        if ($key === null) return $_POST;
        return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
    }

    // Get GET data safely
    protected function getGet($key = null, $default = '') {
        if ($key === null) return $_GET;
        return isset($_GET[$key]) ? trim($_GET[$key]) : $default;
    }

    // Set flash message
    protected function setFlash($type, $message) {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    // Get flash message
    protected function getFlash() {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    // JSON response for AJAX
    protected function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    // Validate CSRF token
    protected function validateCsrf() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $this->json(['error' => 'Invalid CSRF token'], 403);
        }
    }

    // Generate CSRF token
    protected function generateCsrf() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}
