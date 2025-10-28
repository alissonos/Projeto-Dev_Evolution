<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dev Evolution</title>
    <link rel="stylesheet" href="./../../public/css/login.css">
</head>

<body>
    <div class="login-container">
        <h1>Login</h1>
        <form method="POST" action="/">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="seu@email.com">
            </div>

            <div class="form-group">
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required placeholder="Digite sua senha">
            </div>
            <button type="submit">Entrar</button>
            <div class="signup-link">
                Não tem uma conta? Cadastre aqui
                <a href="/signup">Criar conta</a>
            </div>
        </form>
    </div>
</body>

</html>