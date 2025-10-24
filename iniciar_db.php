<?php

require_once __DIR__ . '/src/Infrastructure/Database.php';

use src\Infrastructure\Database;

try {
    $pdo = Database::getInstance();

    echo 'Conexão com o banco de dados estabelecida com sucesso.<br>';

    // 1. DEFINE O SQL PARA CRIAR A TABELA 'Cliente'
    $sqlCreateCliente = '
        CREATE TABLE IF NOT EXISTS cliente (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nome TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL, -- Adicionei UNIQUE ao email, é boa prática
            telefone TEXT,
            endereco TEXT
        );
    ';

    // 2. DEFINE O SQL PARA CRIAR A TABELA 'Compras'
    $sqlCreateCompras = '
        CREATE TABLE IF NOT EXISTS compras (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            clienteId INTEGER NOT NULL,
            produtoId INTEGER NOT NULL,
            quantidade INTEGER NOT NULL,
            dataCompra TEXT NOT NULL,
            FOREIGN KEY (clienteId) REFERENCES cliente(id) 
            -- CORRIGIDO: Removida a vírgula extra aqui ^
        );
    ';

    // 3. DEFINE O SQL PARA CRIAR A TABELA 'Produtos'
    $sqlCreateProdutos = '
        CREATE TABLE IF NOT EXISTS produtos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nome TEXT NOT NULL,
            descricao TEXT,
            preco REAL NOT NULL,
            quantidade INTEGER NOT NULL
        );
    ';

    // 4. DEFINE O SQL PARA CRIAR A TABELA 'Usuarios'
    $sqlCreateUsuarios = '
        CREATE TABLE IF NOT EXISTS usuarios (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            email TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            fullName TEXT NOT NULL
        );
    ';
    // EXECUTA OS COMANDOS DE CRIAÇÃO DAS TABELAS
    $pdo->exec($sqlCreateCliente);
    $pdo->exec($sqlCreateCompras);
    $pdo->exec($sqlCreateProdutos);
    $pdo->exec($sqlCreateUsuarios);

    echo "Tabelas 'cliente', 'compras', 'produtos' e 'usuarios' criadas/verificadas com sucesso.<br>";
} catch (\PDOException $e) {
    echo 'Erro ao inicializar o banco de dados: ' . $e->getMessage();
}
