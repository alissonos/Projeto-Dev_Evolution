<?php

namespace src\Infrastructure;

use src\Infrastructure\Database;
use PDO;

class UsuariosRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function inserir(array $dados): bool
    {
        $sql = 'INSERT INTO usuarios (username, email, password, fullName) 
            VALUES (:username, :email, :password, :fullName)';

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':username' => $dados['username'],
            ':password' => $dados['password'],
            ':email' => $dados['email'],
            ':fullName' => $dados['fullName']
        ]);
    }

    public function listar(): array
    {
        $sql = 'SELECT id, username, email, fullName
            FROM usuarios ORDER BY id';

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id_usuario): ?array
    {
        $sql = 'SELECT id, username, email, fullName
                FROM usuarios 
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
        return $cliente ?: null;
    }

    public function atualizar(int $id, array $dados): bool
    {
        $sql = 'UPDATE usuarios 
            SET username = :username, 
                email = :email, 
                password = :password, 
                fullName = :fullName 
            WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':username' => $dados['username'],
            ':email' => $dados['email'],
            ':password' => $dados['password'],
            ':fullName' => $dados['fullName']
        ]);
    }

    public function deletar(int $id): bool
    {
        $sql = 'DELETE FROM usuarios WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function authenticate(string $email, string $password): array|false
    {
        $sql = 'SELECT id, email, password, fullName 
                FROM usuarios 
                WHERE username = :login_input OR email = :login_input';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':login_input', $email, \PDO::PARAM_STR);
        $stmt->execute();

        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$usuario) {
            return false;
        }

        if ($password === $usuario['password']) {
            unset($usuario['password']);

            return $usuario;
        } else {
            return false;
        }
    }

    public function findAll(): array
    {
        $sql = 'SELECT id, username, email, fullName
            FROM usuarios ORDER BY id';

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
