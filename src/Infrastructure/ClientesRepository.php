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

    public function buscarTodos(): array
    {
        return $this->findAll();
    }

    public function contarTodos(): int
    {
        $sql = 'SELECT COUNT(*) FROM clientes';
        $stmt = $this->pdo->query($sql);

        return (int) $stmt->fetchColumn();
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = 'SELECT usuarioId, nome, email, telefone, endereco
                FROM clientes 
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
        return $cliente ?: null;
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
        $sql = 'SELECT id, usuarioId, nome, email, telefone, endereco
            FROM clientes ORDER BY id';
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
