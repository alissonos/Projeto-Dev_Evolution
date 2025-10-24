<?php

require_once 'vendor/autoload.php';
require_once 'src/Infrastructure/Database.php';
require_once 'src/Infrastructure/UsuariosRepository.php';

use src\Infrastructure\Database;
use src\Infrastructure\UsuariosRepository;

$userName = 'admin';
$password = '123456';
$email = 'alisson@gmail.com';
$fullName = 'Alisson Silva';

$dados_usuario = [
    'username' => $userName,
    'email' => $email,
    'password' => $password,
    'fullName' => $fullName,
];

try {
    $repo = new UsuariosRepository();

    $db = Database::getInstance();
    $stmt = $db->prepare('SELECT COUNT(*) FROM usuarios WHERE email = :email');
    $stmt->execute([':email' => $email]);
    if ($stmt->fetchColumn() > 0) {
        echo "Usuário '{$email}' já existe. Ignorando a inserção.\n";
        exit;
    }

    if ($repo->inserir($dados_usuario)) {
        echo "✅ Usuário inserido com sucesso!\n";
        echo "Dados:\n";
        echo '   Username/Email: ' . $email . "\n";
        echo '   Senha (texto puro): ' . $password . "\n";
    } else {
        echo "Falha ao inserir o Usuário de Teste.\n";
    }
} catch (Exception $e) {
    echo 'Erro durante o seeding: ' . $e->getMessage() . "\n";
}
