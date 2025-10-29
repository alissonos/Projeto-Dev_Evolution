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

        $usuarioId = $_SESSION['id_usuario'] ?? null;
        $produtos = [];
        $nome_usuario = $_SESSION['nome'] ?? 'Usuário';

        if (!$usuarioId) {
            header('Location: /');
            exit;
        }

        $cliente = $this->clientesRepo->buscarUsuarioPorId($usuarioId);

        if (is_array($cliente) && isset($cliente['id'])) {

            // Linha 57 corrigida: Acessamos 'id' com segurança.
            $clienteId = $cliente['id'];
            $nome_usuario = $cliente['nome']; // O nome do cliente é Artur (que possui os produtos)

            // Linha 60 corrigida: Chamada segura.
            // Se este for o método que você implementou, certifique-se que o nome do método 
            // no Controller e no Repositório coincidem (buscarPorClienteId/findByClienteId).
            $produtos = $this->produtosRepo->buscarPorClienteId($clienteId);
        }

        require_once dirname(__DIR__, 2) . '/src/Views/Dashboard.php';
    }
}
