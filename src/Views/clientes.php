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
            <h2>Formulário de Cadastro de Cliente</h2>

            <form action="/cliente/cadastro" method="POST">

                <div class="form-group">
                    <label for="nome">Nome Completo:</label>
                    <input type="text" id="nome" name="nome" required
                        placeholder="Digite o nome completo do cliente">
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required
                        placeholder="cliente@exemplo.com">
                </div>

                <div class="form-group">
                    <label for="username">Login:</label>
                    <input type="text" id="username" name="username" required placeholder="Seu login">
                </div>

                <div class="form-group">
                    <label for="password">Senha:</label>
                    <input type="password" id="password" name="password" required placeholder="Digite sua senha">
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone (Opcional):</label>
                    <input type="tel" id="telefone" name="telefone"
                        placeholder="(99) 99999-9999">
                </div>

                <div class="form-group">
                    <label for="endereco">Endereço (Opcional):</label>
                    <textarea id="endereco" name="endereco" rows="3"
                        placeholder="Rua, número, bairro e cidade."></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-primary">Cadastrar Cliente</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>