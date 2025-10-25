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
        $sql = 'INSERT INTO clientes (usuarioId, nome, email, telefone, endereco) 
            VALUES (:usuarioId, :nome, :email, :telefone, :endereco)';

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':usuarioId' => $dados['usuarioId'],
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
        $sql = 'UPDATE clientes 
                SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco 
                WHERE id = :id_where';

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id_where' => $id,
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
        $sql = 'SELECT id, nome, email, telefone, endereco, data_cadastro 
            FROM clientes ORDER BY id';
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
