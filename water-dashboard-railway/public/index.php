<?php
// ============================================================
// public/index.php - Application Entry Point
// ============================================================

// Load configuration
require_once dirname(__DIR__) . '/config/config.php';

// Load and boot application
require_once BASE_PATH . '/app/core/App.php';

new App();
