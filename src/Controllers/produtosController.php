<?php

namespace src\Controllers;

use src\Infrastructure\Database;
use src\Infrastructure\ProdutosRepository;
use src\Infrastructure\ClientesRepository;

class ProdutosController
{
    private ClientesRepository $clientesRepo;
    private ProdutosRepository $produtosRepo;

    public function __construct()
    {
        $db = Database::getInstance();

        $this->produtosRepo = new ProdutosRepository($db);

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function showProduto()
    {
        require_once dirname(__DIR__) . '/Views/produtos.php';
    }


    public function cadastrar()
    {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $telefone = $_POST['telefone'] ?? null;
        $endereco = $_POST['endereco'] ?? null;

        if (empty($nome) || empty($email)) {
            $_SESSION['error_message'] = "Nome e Email são obrigatórios para o cadastro.";
            header('Location: /produto/cadastro');
            exit;
        }

        $dadosCliente = [
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone,
            'endereco' => $endereco
        ];

        $sucesso = $this->clientesRepo->inserir($dadosCliente);

        if ($sucesso) {
            $_SESSION['success_message'] = "Cliente '{$nome}' cadastrado com sucesso!";
            header('Location: /dashboard');
        } else {
            $_SESSION['error_message'] = "Erro ao cadastrar o cliente. Tente novamente.";
            header('Location: /cliente/cadastro');
        }
        exit;
    }
}
