<?php

if (!isset($_SESSION['id_usuario'])) {
    header('Location: /login');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$nome_usuario = $_SESSION['nome'];
$email = $_SESSION['email'];

use src\Infrastructure\ClientesRepository;
use src\Infrastructure\ComprasRepository;
use src\Infrastructure\Database;
use src\Infrastructure\ProdutosRepository;

$db = Database::getInstance();

$clientesRepository = new ClientesRepository($db);
$cliente_logado = $clientesRepository->buscarPorId($id_usuario);

$cliente_id = $cliente_logado['id'] ?? 0;

$total_clientes = $clientesRepository->contarTodos();
$lista_clientes = $clientesRepository->buscarTodos();

$produtosRepository = new ProdutosRepository($db);
//$produtos_do_cliente = $produtosRepository->buscarPorClienteId($cliente_id);

$comprasRepository = new ComprasRepository($db);
$compras_do_cliente = $comprasRepository->buscarPorClienteId($cliente_id);

$compras_cliente_logado = $comprasRepository->buscarPorClienteId($cliente_id);

$compras_por_produto = [];

foreach ($compras_cliente_logado as $compra) {
    $produtoId = $compra['produtoId'];
    $quantidadeComprada = $compra['quantidadeComprada'];

    $compras_por_produto[$produtoId] = ($compras_por_produto[$produtoId] ?? 0) + $quantidadeComprada;
}

//$total_produtos_cliente = count($produtos_do_cliente);
$total_compras_cliente = count($compras_do_cliente);
$clientes_lista = $lista_clientes;
$total_de_clientes = count($lista_clientes);

$produtoDevEvolution = $produtosRepository->buscarPorId(1);
$produtoOpaEvolution = $produtosRepository->buscarPorId(2);

$quantidadeCompradaDev = $compras_por_produto[1] ?? 0;
if ($produtoDevEvolution && isset($produtoDevEvolution['quantidade'])) {
    $produtoDevEvolution['quantidade'] -= $quantidadeCompradaDev;
}

$quantidadeCompradaOpa = $compras_por_produto[2] ?? 0;
if ($produtoOpaEvolution && isset($produtoOpaEvolution['quantidade'])) {
    $produtoOpaEvolution['quantidade'] -= $quantidadeCompradaOpa;
}

$produtosDisponiveis = $produtosRepository->listar();

unset($total_produtos_cliente);
unset($total_compras_cliente);
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Dashboard de Clientes</title>
    <link rel="stylesheet" href="./../../public/css/dashboard.css">
</head>

<body>

    <div class="header-container">
        <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['nome']) ?>, a sua Área de Ingressos</h1>
        <a href="/logout">Logout</a>
    </div>

    <div>
        <div class="header-produtos">
            <h1>Produtos disponíveis</h1>
        </div>

        <div class="produtos-disponiveis-container">
            <h2>
                Ingresso Curso DEV Evolution
                <div class="estoque-disponivel">
                    Disponível:
                    <?php
                    if ($produtoDevEvolution && isset($produtoDevEvolution['quantidade'])) {
                        echo htmlspecialchars($produtoDevEvolution['quantidade']);
                    } else {
                        echo 'N/A';
                    }
                    ?>
                    Ingressos
                </div>
            </h2>

            <h2>
                Ingresso Evento Opa Evolution
                <div class="estoque-disponivel">
                    Disponível:
                    <?php
                    if ($produtoOpaEvolution && isset($produtoOpaEvolution['quantidade'])) {
                        echo htmlspecialchars($produtoOpaEvolution['quantidade']);
                    } else {
                        echo 'N/A';
                    }
                    ?>
                    Ingressos
                </div>
            </h2>
        </div>
    </div>

    <div class="box-compra">
        <h2>Realizar Compra</h2>

        <form action="/comprar" method="POST">

            <div class="form-group">
                <label for="cliente_nome">Comprador:</label>
                <input type="name" id="name" name="name" required placeholder="Digite seu nome completo">

                <input type="hidden" name="cliente_id" value="<?= htmlspecialchars($cliente_id ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="seu@email.com">
            </div>
            <div class="form-group">
                <label for="produto_id">Escolha o seu produto:</label>
                <select id="produto_id" name="produto_id" required>
                    <option value="">-- Selecione --</option>

                    <?php
                    if (!empty($produtosDisponiveis) && is_array($produtosDisponiveis)):
                        foreach ($produtosDisponiveis as $produto):
                    ?>
                            <option value="<?= htmlspecialchars($produto['id']); ?>">
                                <?= htmlspecialchars($produto['nome']); ?>
                            </option>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="quantidade">Quantidade a Comprar:</label>
                <input type="number"
                    id="quantidade"
                    name="quantidade"
                    min="1"
                    value="1"
                    required
                    style="width: 100px;">
            </div>

            <div class="form-group">
                <button type="submit" class="btn-primary">Adicionar ao Carrinho</button>
            </div>
        </form>
    </div>

    <?php if (empty($lista_clientes)): ?>
        <!-- <p>Nenhum cliente cadastrado no sistema.</p> -->
    <?php else: ?>
        <?php foreach ($lista_clientes as $cliente): ?>
            <?php
            $id_cliente_atual = $cliente['id'] ?? 0;

            //$produtos_do_cliente_atual = $produtosRepository->buscarPorClienteId($id_cliente_atual);

            $compras_do_cliente_atual = $comprasRepository->buscarPorClienteId($id_cliente_atual);
            ?>

            <div class="cliente-card">

                <div class="card-header" onclick="toggleCard(this)">
                    <h3><?= htmlspecialchars($cliente['nome']) ?></h3>
                </div>

                <div class="card-content hidden">
                    <p>Email: <?= htmlspecialchars($cliente['email']) ?></p>
                    <p>Telefone: <?= htmlspecialchars($cliente['telefone'] ?? 'N/A') ?></p>

                    <!--  <h4>Produtos Cadastrados</h4>
                    <?php if (empty($produtos_do_cliente_atual)): ?>
                        <p>Nenhum produto encontrado.</p>
                    <?php else: ?>
                        <ul>
                            <?php foreach ($produtos_do_cliente_atual as $produto): ?>
                                <li>
                                    <?= htmlspecialchars($produto['nome']) ?>(Preço: R$<?= number_format($produto['preco'], 2, ',', '.') ?> |
                                    Qtd: <?= htmlspecialchars($produto['quantidade']) ?>)
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?> -->

                    <h4>Produtos no Carrinho</h4>
                    <?php if (empty($compras_do_cliente_atual)): ?>
                        <p>Nenhuma compra registrada.</p>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Quantidade</th>
                                    <th>Descrição</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($compras_do_cliente_atual as $compra): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($compra['quantidadeComprada'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($compra['descricaoProduto'] ?? 'Descrição não disponível') ?></td>
                                        <td>R$<?= number_format($compra['valorTotal'] ?? 0, 2, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>

                    <hr>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- /*Script para fazer o Wrap -->
    <script>
        function toggleCard(headerElement) {
            const content = headerElement.nextElementSibling;

            if (content && content.classList.contains('card-content')) {
                content.classList.toggle('hidden');
            }
        }
    </script>
</body>

</html>