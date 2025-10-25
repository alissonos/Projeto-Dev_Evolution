<?php

require_once __DIR__ . '/src/Infrastructure/Database.php';

use src\Infrastructure\Database;

try {
    $pdo = Database::getInstance();

    echo 'Conexão com o banco de dados estabelecida com sucesso.' . PHP_EOL;

    $pdo->exec('PRAGMA foreign_keys = ON;');

    $pdo->exec('DROP TABLE IF EXISTS compras;');
    $pdo->exec('DROP TABLE IF EXISTS produtos;');
    $pdo->exec('DROP TABLE IF EXISTS clientes;');
    $pdo->exec('DROP TABLE IF EXISTS usuarios;');

    $sqlCreateUsuarios = '
        CREATE TABLE IF NOT EXISTS usuarios (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            email TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            fullName TEXT NOT NULL
        );
    ';

    $sqlCreateclientes = '
        CREATE TABLE IF NOT EXISTS clientes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            usuarioId INTEGER NOT NULL,
            nome TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            telefone TEXT,
            endereco TEXT,
            FOREIGN KEY (usuarioId) REFERENCES usuarios(id)
        );
    ';

    $sqlCreateProdutos = '
        CREATE TABLE IF NOT EXISTS produtos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            clienteId INTEGER NOT NULL, 
            nome TEXT NOT NULL,
            descricao TEXT,
            preco REAL NOT NULL,
            quantidade INTEGER NOT NULL,
            FOREIGN KEY (clienteId) REFERENCES clientes(id)
        );
    ';

    $sqlCreateCompras = '
        CREATE TABLE IF NOT EXISTS compras (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            clienteId INTEGER NOT NULL,
            produtoId INTEGER NOT NULL,
            quantidade INTEGER NOT NULL,
            dataCompra TEXT NOT NULL,
            FOREIGN KEY (clienteId) REFERENCES clientes(id),
            FOREIGN KEY (produtoId) REFERENCES produtos(id)
        );
    ';

    $pdo->exec($sqlCreateUsuarios);
    $pdo->exec($sqlCreateclientes);
    $pdo->exec($sqlCreateProdutos);
    $pdo->exec($sqlCreateCompras);

    echo "Tabelas 'usuarios', 'clientes', 'produtos' e 'compras' criadas/verificadas com sucesso com as novas chaves estrangeiras." . PHP_EOL;
} catch (\PDOException $e) {
    echo 'Erro ao inicializar o banco de dados: ' . $e->getMessage();
}
