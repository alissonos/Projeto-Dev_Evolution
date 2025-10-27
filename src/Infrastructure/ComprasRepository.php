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
        $sql = 'INSERT INTO compras (clienteId, produtoId, quantidade, valorTotal, dataCompra) 
            VALUES (:clienteId, :produtoId, :quantidade, :valorTotal, :dataCompra)';

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':clienteId' => $dados['clienteId'],
            ':produtoId' => $dados['produtoId'],
            ':quantidade' => $dados['quantidade'],
            ':valorTotal' => $dados['valorTotal'],
            ':dataCompra' => $dados['dataCompra']
        ]);
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = 'SELECT clienteId, produtoId, quantidade, dataCompra
                FROM compras 
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
        return $cliente ?: null;
    }

    public function buscarPorClienteId(int $clienteId): array
    {
        $sql = 'SELECT 
                c.id AS compraId, 
                c.produtoId, 
                c.quantidade AS quantidadeComprada, 
                c.dataCompra, 
                c.valorTotal,
                p.nome AS nomeProduto,
                p.descricao AS descricaoProduto
            FROM 
                compras c
            INNER JOIN 
                produtos p ON c.produtoId = p.id
            WHERE 
                c.clienteId = :clienteId';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':clienteId' => $clienteId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
