<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - DEV Evolution</title>
    <link rel="stylesheet" href="./public/css/dashboard.css">
</head>
<body>

    <h1>Bem-vindo ao Painel de Controle!</h1>
    <a href="/logout">Sair</a>
    
    <h2>Clientes (<?= count($clientes ?? []) ?>)</h2>
    <?php if (!empty($clientes)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?= htmlspecialchars($cliente->getId()) ?></td>
                    <td><?= htmlspecialchars($cliente->getNome()) ?></td>
                    <td><?= htmlspecialchars($cliente->getEmail()) ?></td>
                    <td><?= htmlspecialchars($cliente->getTelefone()) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum cliente cadastrado.</p>
    <?php endif; ?>
    
    <h2>Produtos (<?= count($produtos ?? []) ?>)</h2>
    <h2>Compras (<?= count($compras ?? []) ?>)</h2>
    <h2>Usuários (<?= count($usuarios ?? []) ?>)</h2>
    </body>
</html>