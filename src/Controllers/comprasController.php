<?php

namespace src\Controllers;

use src\Infrastructure\Database;
use src\Infrastructure\ComprasRepository;
use src\Infrastructure\ProdutosRepository;

class ComprasController
{
    private ComprasRepository $comprasRepo;
    private ProdutosRepository $produtosRepo;

    public function __construct()
    {
        $db = Database::getInstance();
        $this->comprasRepo = new ComprasRepository($db);
        $this->produtosRepo = new ProdutosRepository($db);

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function estoque()
    {
        $clienteId = (int) ($_POST['clienteId'] ?? 0);
        $produtoId = (int) $_POST['produtoId'] ?? null;
        $quantidade = (int) ($_POST['quantidade'] ?? 0);

        if (!$clienteId || !$produtoId || $quantidade <= 0) {
            $_SESSION['error_message'] = "Dados de compra inválidos.";
            header('Location: /dashboard');
            exit;
        }

        $produto = $this->produtosRepo->buscarPorId($produtoId);

        if (!$produto || !isset($produto['preco'])) {
            $_SESSION['error_message'] = "Produto não encontrado ou sem preço.";
            header('Location: /dashboard');
            exit;
        }

        $precoUnitario = $produto['preco'];
        $valorTotal = $precoUnitario * $quantidade;

        $dadosCompra = [
            'clienteId' => (int)$clienteId,
            'produtoId' => (int)$produtoId,
            'quantidade' => $quantidade,
            'valorTotal' => $valorTotal,
            'dataCompra' => date('Y-m-d H:i:s')
        ];

        $sucesso = $this->comprasRepo->inserir($dadosCompra);

        if ($sucesso) {
            $_SESSION['success_message'] = "Compra realizada com sucesso!";
        } else {
            $_SESSION['error_message'] = "Erro ao registrar a compra.";
        }

        // Redireciona de volta para o Dashboard
        header('Location: /dashboard');
        exit;
    }
}
