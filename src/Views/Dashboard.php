<?php

$id_usuario = $_SESSION['id_usuario'];
$nome_usuario = $_SESSION['nome'];
$email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Dashboard - DEV Evolution</title>
        <link rel="stylesheet" href="./public/css/dashboard.css">
    </head>
    <body>

        <div class="header">
            <h1>Bem-vindo, <?= htmlspecialchars($nome_usuario) ?>, a sua Área de Ingressos para o DEV Evolution!</h1>
            <button class="logout-button">
                <a href="/logout">Sair</a>
            </button>
        </div>

        <div>
            <h2>Quantidade de Ingressos Disponíveis</h2>
        </div>

        <div>
            <h2>Clientes (<?= htmlspecialchars($colocarOsClientesOuFazerUmForeachDeClientes) ?>)</h2>
            <h2>Produtos (<?= count($produtos ?? []) ?>)</h2>
            <h2>Compras (<?= count($compras ?? []) ?>)</h2>
        </div>
    </body>
</html>