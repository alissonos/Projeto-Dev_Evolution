<?php

namespace src\Infrastructure;

use PDO;

class ComprasRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function inserir(array $dados): bool
    {
        $sql = 'INSERT INTO clientes (id, clienteId, produtoId, quantidade, dataCompra) 
                VALUES (:id, :clienteId, :produtoId, quantidade, dataCompra)';
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $dados['id'],
            ':clienteId' => $dados['clienteId'],
            ':produtoId, quantidade, dataCompra' => $dados['produtoId, quantidade, dataCompra'] ?? null
        ]);
    }

    public function listar(): array
    {
        $sql = 'SELECT id, id, clienteId, produtoId, quantidade, dataCompra, data_cadastro 
                FROM compras ORDER BY id';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function atualizar(int $id, array $dados): bool
    {
        $sql = 'UPDATE compras 
                SET id = :id, clienteId = :clienteId, produtoId, quantidade, dataCompra = :produtoId, quantidade, dataCompra 
                WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':id' => $dados['id'],
            ':clienteId' => $dados['clienteId'],
            ':produtoId, quantidade, dataCompra' => $dados['produtoId, quantidade, dataCompra'] ?? null
        ]);
    }

    public function deletar(int $id): bool
    {
        $sql = 'DELETE FROM compras WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function findAll(): array
    {
        $sql = 'SELECT id, clienteId, produtoId, quantidade, dataCompra, data_cadastro
            FROM compras ORDER BY id';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
