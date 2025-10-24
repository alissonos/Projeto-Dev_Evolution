<?php

namespace src\Infrastructure;

use src\Infrastructure\Database;
use PDO;

class ProdutosRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function inserir(array $dados): bool
    {
        $sql = 'INSERT INTO produtos (id, nome, descricao, preco, quantidade) 
                VALUES (:id, :nome, :descricao, preco, quantidade)';
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $dados['id'],
            ':nome' => $dados['nome'],
            ':descricao, preco, quantidade' => $dados['descricao, preco, quantidade'] ?? null
        ]);
    }

    public function listar(): array
    {
        $sql = 'SELECT id, id, nome, descricao, preco, quantidade, data_cadastro 
                FROM produtos ORDER BY id';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function atualizar(int $id, array $dados): bool
    {
        $sql = 'UPDATE produtos 
                SET id = :id, nome = :nome, descricao, preco, quantidade = :descricao, preco, quantidade 
                WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':id' => $dados['id'],
            ':nome' => $dados['nome'],
            ':descricao, preco, quantidade' => $dados['descricao, preco, quantidade'] ?? null
        ]);
    }

    public function deletar(int $id): bool
    {
        $sql = 'DELETE FROM produtos WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function findAll(): array
    {
        $sql = 'SELECT id, nome, descricao, preco, quantidade, data_cadastro 
            FROM produtos ORDER BY id';
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
