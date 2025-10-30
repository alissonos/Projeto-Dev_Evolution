<?php
extract($dados);

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

    <div class="app-layout">

        <aside class="sidebar">
            <div class="logo">Opa Evolution</div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="/dashboard">Dashboard</a></li>
                    <li class="separator"></li>
                    <li><a href="/cliente/cadastro">Cadastrar Cliente</a></li>
                    <li><a href="/produto/cadastro">Cadastrar Produto</a></li>
                    <li class="separator"></li>
                </ul>
            </nav>

            <div class="logout-link">
                <a href="/logout">Sair</a>
            </div>
        </aside>

        <main class="main-content">
            <header class="main-header">
                Bem-vindo, <?= htmlspecialchars($nome_usuario) ?>, a sua Área de Ingressos
            </header>

            <div class="dashboard-body">
                <div class="box-compra">
                    <h2>Realizar Compra</h2>

                    <form action="/compra" method="POST">

                        <div class="form-group">
                            <label for="clienteId">Cliente:</label>
                            <input type="name" id="clienteNome" name="clienteNome" required placeholder="Digite seu nome completo">

                            <input type="hidden" name="clienteId" value="<?= htmlspecialchars($cliente_id ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="produtoId">Escolha o seu produto:</label>
                            <select id="produtoId" name="produtoId" required>
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
            </div>
        </main>
    </div>
</body>

</html>