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
        $sql = 'INSERT INTO produtos (clienteId, nome, descricao, preco, quantidade) 
            VALUES (:clienteId, :nome, :descricao, :preco, :quantidade)';

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':clienteId' => $dados['clienteId'],
            ':nome' => $dados['nome'],
            ':descricao' => $dados['descricao'] ?? null,
            ':preco' => $dados['preco'],
            ':quantidade' => $dados['quantidade']
        ]);
    }

    public function listar(): array
    {
        $sql = 'SELECT id, nome, descricao, preco, quantidade, data_cadastro 
                FROM produtos ORDER BY id';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = 'SELECT id AS produtoId, clienteId, nome, descricao, preco, quantidade
             FROM produtos 
             WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        return $produto ?: null;
    }

    public function buscarPorClienteId(int $clienteId): array
    {
        $sql = 'SELECT id AS produtoId, clienteId, nome, descricao, preco, quantidade
            FROM produtos 
            WHERE clienteId = :clienteId';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':clienteId' => $clienteId]);
        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $produtos;
    }

     public function buscarPrecoPorId(int $produtoId): ?float
    {
        $sql = 'SELECT preco FROM produtos WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $produtoId]);

        $preco = $stmt->fetchColumn();

        return $preco !== false ? (float) $preco : null;
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
