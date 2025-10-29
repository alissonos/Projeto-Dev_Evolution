<?php

use src\Infrastructure\Database;
use src\Infrastructure\UsuariosRepository;

class AuthController
{
    private UsuariosRepository $userRepository;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $db = Database::getInstance();
        $this->userRepository = new UsuariosRepository($db);
    }

    public function showLogin(): void
    {
        require_once dirname(__DIR__) . '/Views/login.php';
    }

    public function showSignup(): void
    {
        require_once dirname(__DIR__) . '/Views/signup.php';
    }

    public function login(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $usuario = $this->userRepository->authenticate($email, $password);

        if ($usuario) {
            $_SESSION['autenticado'] = true;
            $_SESSION['id_usuario'] = $usuario['id'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['nome'] = $usuario['fullName'];

            header('Location: /dashboard');
            exit;
        }
        $error = 'Usuário ou senha inválidos.';
        $this->showLogin($error);
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: /');
        exit;
    }
}
