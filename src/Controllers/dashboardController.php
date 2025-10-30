<?php

namespace src\Controllers;

use src\Infrastructure\ClientesRepository;
use src\Infrastructure\ComprasRepository;
use src\Infrastructure\Database;
use src\Infrastructure\ProdutosRepository;
use src\Infrastructure\UsuariosRepository;

class DashboardController
{
    private ClientesRepository $clientesRepo;
    private ProdutosRepository $produtosRepo;
    private ComprasRepository $comprasRepo;
    private UsuariosRepository $usuariosRepo;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
            header('Location: /');
            exit;
        }

        $db = Database::getInstance();
        $this->clientesRepo = new ClientesRepository($db);
        $this->produtosRepo = new ProdutosRepository($db);
        $this->comprasRepo = new ComprasRepository($db);
        $this->usuariosRepo = new UsuariosRepository($db);
    }

    public function index()
    {
        // 1. Variáveis Iniciais
        $usuarioId = $_SESSION['id_usuario'] ?? null;
        $nome_usuario = $_SESSION['nome'] ?? 'Usuário';

        if (!$usuarioId) {
            header('Location: /');
            exit;
        }

        $cliente_logado = $this->clientesRepo->buscarPorId($usuarioId);

        $cliente_id = $cliente_logado['id'] ?? 0;
        $nome_usuario = $cliente_logado['nome'] ?? $nome_usuario;

        $produtoDevEvolution = $this->produtosRepo->buscarPorId(1);
        $produtoOpaEvolution = $this->produtosRepo->buscarPorId(2);
        $produtosDisponiveis = $this->produtosRepo->listar();

        // Compras do Cliente Logado (para cálculo do estoque)
        $compras_cliente_logado = $this->comprasRepo->buscarPorClienteId($cliente_id);
        $compras_por_produto = [];

        foreach ($compras_cliente_logado as $compra) {
            $produtoId = $compra['produtoId'];
            $quantidadeComprada = $compra['quantidadeComprada'];

            $compras_por_produto[$produtoId] = ($compras_por_produto[$produtoId] ?? 0) + $quantidadeComprada;
        }

        // Atualizar Estoque Disponível
        $quantidadeCompradaDev = $compras_por_produto[1] ?? 0;
        if ($produtoDevEvolution && isset($produtoDevEvolution['quantidade'])) {
            $produtoDevEvolution['quantidade'] -= $quantidadeCompradaDev;
        }

        $quantidadeCompradaOpa = $compras_por_produto[2] ?? 0;
        if ($produtoOpaEvolution && isset($produtoOpaEvolution['quantidade'])) {
            $produtoOpaEvolution['quantidade'] -= $quantidadeCompradaOpa;
        }

        $lista_clientes = $this->clientesRepo->buscarTodos();

        $compras_por_cliente_id = [];
        foreach ($lista_clientes as $cliente) {
            $id_cliente_atual = $cliente['id'] ?? 0;
            $compras_por_cliente_id[$id_cliente_atual] = $this->comprasRepo->buscarPorClienteId($id_cliente_atual);
        }


        $dados = [
            'nome_usuario' => $nome_usuario,
            'cliente_id' => $cliente_id,
            'produtoDevEvolution' => $produtoDevEvolution,
            'produtoOpaEvolution' => $produtoOpaEvolution,
            'produtosDisponiveis' => $produtosDisponiveis,
            'lista_clientes' => $lista_clientes,
            'compras_por_cliente_id' => $compras_por_cliente_id,
            'comprasRepository' => $this->comprasRepo
        ];

        require_once dirname(__DIR__, 2) . '/src/Views/dashboard.php';
    }
}
