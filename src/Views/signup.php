<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Infrastructure/Database.php';
require_once __DIR__ . '/../Infrastructure/UsuariosRepository.php';

use src\Infrastructure\Database;
use src\Infrastructure\UsuariosRepository;
use src\Models\Usuarios;

$db = Database::getInstance();
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        empty($_POST['fullName']) || empty($_POST['username']) ||
        empty($_POST['email']) || empty($_POST['password'])
    ) {
        $mensagem = 'Todos os campos são obrigatórios!';
    } else {

        $dados_usuario = [
            'fullName' => trim($_POST['fullName']),
            'username' => trim($_POST['username']),
            'email' => trim($_POST['email']),
            'password' => $_POST['password']
        ];

        $senha_criptografada = password_hash($dados_usuario['password'], PASSWORD_DEFAULT);

        $usuario = new Usuarios(
            $dados_usuario['username'],
            $dados_usuario['email'],
            $senha_criptografada,
            $dados_usuario['fullName']
        );
        $usuario->setUsername($dados_usuario['username']);
        $usuario->setEmail($dados_usuario['email']);
        $usuario->setPassword($senha_criptografada);
        $usuario->setfullName($dados_usuario['fullName']);

        $usuarioRepository = new UsuariosRepository($db);

        try {
            $sucesso = $usuarioRepository->inserir($usuario);

            if ($sucesso) {
                header('Location: /Login.php');
                exit();
            } else {
                $mensagem = 'Não foi possível cadastrar o usuário.';
            }
        } catch (\PDOException $e) {
            $mensagem = 'Erro no banco de dados: ' . $e->getMessage();
        } catch (\Exception $e) {
            $mensagem = 'Erro ao cadastrar: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dev Evolution</title>
    <link rel="stylesheet" href="./../../public/css/signup.css">
</head>

<body>
    <div class="login-container">
        <h1>Signup</h1>
        <form method="POST" action="/src/Views/Signup.php">
            <div class="form-group">
                <label for="fullName">Nome completo:</label>
                <input type="text" id="fullName" name="fullName" required placeholder="Nome completo">
            </div>
            <div class="form-group">
                <label for="username">Login:</label>
                <input type="text" id="username" name="username" required placeholder="Seu login">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="seu@email.com">
            </div>
            <div class="form-group">
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required placeholder="Digite sua senha">
            </div>
            <button type="submit">Criar Conta</button>
            <div class="login-link">
                Já tem uma conta?
                <a href="/login">Faça Login</a>
            </div>
        </form>
    </div>
</body>

</html>