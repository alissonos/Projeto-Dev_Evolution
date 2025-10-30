<?php

define('ROOT_PATH', __DIR__);


require_once 'vendor/autoload.php';

require_once __DIR__ . '/src/Controllers/authController.php';
require_once __DIR__ . '/src/Controllers/dashboardController.php';
require_once __DIR__ . '/src/Controllers/comprasController.php';
require_once __DIR__ . '/src/Controllers/clientesController.php';

use src\Controllers\DashboardController;
use src\Controllers\AuthController;
use src\Controllers\ComprasController;
use src\Controllers\ClientesController;

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($path === '/' || $path === '/index.php') {
    $controller = new authController();

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
} elseif ($path === '/cliente/cadastro' && $method === 'GET') {
    $controller = new ClientesController();
    $controller->showCadastro();
} elseif ($path === '/cliente/cadastro' && $method === 'POST') {
    $controller = new ClientesController();
    $controller->cadastrar();
} elseif ($path === '/compra' && $method === 'POST') {
    $controller = new ComprasController();
    $controller->estoque();
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
