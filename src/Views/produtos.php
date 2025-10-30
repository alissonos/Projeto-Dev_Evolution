<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Dashboard de Clientes</title>
    <link rel="stylesheet" href="./../../public/css/clientes.css">
</head>

<body>
    <main class="main-content">
        <div class="cadastro-container">
            <h2>Formulário de Cadastro de Produto</h2>

            <form action="/cliente/cadastro" method="POST">

                <div class="form-group">
                    <label for="nome">Produto:</label>
                    <input type="text" id="nome" name="nome" required
                        placeholder="Digite o nome completo do cliente">
                </div>

                <div class="form-group">
                    <label for="text">Descreva o produto:</label>
                    <input type="email" id="email" name="email" required
                        placeholder="Descreva sobre o seu produto">
                </div>

                <div class="form-group">
                    <label for="preco">Preço:</label>
                    <input type="price" id="telefone" name="telefone"
                        placeholder="R$">
                </div>

                <div class="form-group">
                    <label for="quantidade">Quantidade:</label>
                    <input type="number" id="quantidade" name="quantidade" placeholder="Mínimo uma quantidade" min="1" value="1">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-primary">Cadastrar Produto</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>