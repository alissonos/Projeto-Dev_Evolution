<?php

define('ROOT_PATH', __DIR__);

use src\Controllers\DashboardController;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/src/Controllers/AuthController.php';
require_once __DIR__ . '/src/Controllers/DashboardController.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($path === '/' || $path === '/index.php') {
    $controller = new AuthController();

    if ($method === 'GET') {
        $controller->showLogin();
    } elseif ($method === 'POST') {
        $controller->login();
    }
} elseif ($path === '/dashboard') {
    if ($method === 'GET') {
        $controller = new DashboardController();
        $controller->index();
    }
} elseif ($path === '/signup' && $method === 'GET') {
    $controller = new AuthController();
    $controller->showSignup();
} elseif ($path === '/logout' && $method === 'GET') {
    $controller = new AuthController();
    $controller->logout();
} elseif ($path === '/login' && $method === 'GET') {
    $controller = new AuthController();
    $controller->showLogin();
} else {
    http_response_code(404);
    echo '<h1>404 - Página não encontrada.</h1>';
}
