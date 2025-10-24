<?php

namespace src\Infrastructure;

use PDO;

class ClientesRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)  
    {
        $this->pdo = $pdo;
    }

    public function inserir(array $dados): bool
    {
        $sql = 'INSERT INTO clientes (nome, email, telefone, endereco) 
                VALUES (:nome, :email, :telefone, :endereco)';

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':nome' => $dados['nome'] ?? null,
            ':email' => $dados['email'] ?? null,
            ':telefone' => $dados['telefone'] ?? null,
            ':endereco' => $dados['endereco'] ?? null
        ]);
    }

    public function listar(): array
    {
        return $this->findAll();
    }

    public function atualizar(int $id, array $dados): bool
    {
        // CORREÇÃO: Apenas defina as colunas que estão sendo atualizadas. 'id' é usado no WHERE.
        $sql = 'UPDATE clientes 
                SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco 
                WHERE id = :id_where';  // Uso de placeholder diferente para evitar conflito

        $stmt = $this->pdo->prepare($sql);

        // CORREÇÃO: Cada placeholder deve ser ligado individualmente.
        return $stmt->execute([
            ':id_where' => $id,  // Usa o ID da função no WHERE
            ':nome' => $dados['nome'] ?? null,
            ':email' => $dados['email'] ?? null,
            ':telefone' => $dados['telefone'] ?? null,
            ':endereco' => $dados['endereco'] ?? null
        ]);
    }

    public function deletar(int $id): bool
    {
        $sql = 'DELETE FROM clientes WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function findAll(): array
    {
        // CORRETO: Método de busca simples.
        $sql = 'SELECT id, nome, email, telefone, endereco, data_cadastro 
            FROM clientes ORDER BY id';
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
