<?php
// ============================================================
// app/core/App.php - Application Bootstrap
// ============================================================

class App {
    public function __construct() {
        // Load core files
        require_once BASE_PATH . '/app/core/Database.php';
        require_once BASE_PATH . '/app/core/Model.php';
        require_once BASE_PATH . '/app/core/Controller.php';
        require_once BASE_PATH . '/app/core/Router.php';
        require_once BASE_PATH . '/app/core/Helper.php';

        // Start session
        $this->initSession();

        // Route the request
        $router = new Router();
        $router->route();
    }

    private function initSession() {
        session_name(SESSION_NAME);
        session_set_cookie_params([
            'lifetime' => SESSION_LIFETIME,
            'path'     => '/',
            'secure'   => false, // Set to true in production (HTTPS)
            'httponly' => true,
            'samesite' => 'Strict',
        ]);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
