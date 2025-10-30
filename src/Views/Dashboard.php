<?php

/**
 * src/Views/Dashboard.php
 * * Esta view é incluída pelo DashboardController::index().
 * O array $dados foi passado para este escopo.
 */

// 1. INJETAR OS DADOS DO CONTROLLER
// Transforma as chaves do array $dados em variáveis locais (ex: $dados['nome_usuario'] vira $nome_usuario)
extract($dados);

// Opcional: Remove a variável do repositório, garantindo que não será usada acidentalmente.
if (isset($comprasRepository)) {
    unset($comprasRepository);
}
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
        <h1>Bem-vindo, <?= htmlspecialchars($nome_usuario) ?>, a sua Área de Ingressos</h1>
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
                    // Usa a variável $produtoDevEvolution, que é injetada via extract()
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
                    // Usa a variável $produtoOpaEvolution, que é injetada via extract()
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
                    // Usa a variável $produtosDisponiveis, que é injetada via extract()
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

    <?php
    // Usa a variável $lista_clientes, que é injetada via extract()
    if (empty($lista_clientes)):
    ?>
    <?php else: ?>
        <?php foreach ($lista_clientes as $cliente): ?>
            <?php
            $id_cliente_atual = $cliente['id'] ?? 0;

            // 2. USO DE DADOS PRÉ-CARREGADOS (MELHORIA MVC)
            // Usa o array $compras_por_cliente_id (pré-carregado no Controller)
            // Removemos a chamada de banco de dados: $comprasRepository->buscarPorClienteId($id_cliente_atual);
            $compras_do_cliente_atual = $compras_por_cliente_id[$id_cliente_atual] ?? [];
            ?>

            <div class="cliente-card">

                <div class="card-header" onclick="toggleCard(this)">
                    <h3><?= htmlspecialchars($cliente['nome']) ?></h3>
                </div>

                <div class="card-content hidden">
                    <p>Email: <?= htmlspecialchars($cliente['email']) ?></p>
                    <p>Telefone: <?= htmlspecialchars($cliente['telefone'] ?? 'N/A') ?></p>

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