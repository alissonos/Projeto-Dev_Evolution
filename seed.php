<?php

// Requerindo as dependências necessárias
require_once 'vendor/autoload.php';
require_once 'src/Infrastructure/Database.php';
require_once 'src/Infrastructure/UsuariosRepository.php';
// Adicionar aqui os novos repositórios:
require_once 'src/Infrastructure/ClientesRepository.php';
require_once 'src/Infrastructure/ProdutosRepository.php';
require_once 'src/Infrastructure/ComprasRepository.php';

use src\Infrastructure\ClientesRepository;
use src\Infrastructure\ComprasRepository;  // Adicione esta linha
use src\Infrastructure\Database;
use src\Infrastructure\ProdutosRepository;
use src\Infrastructure\UsuariosRepository;

// --- DADOS BÁSICOS ---
$db = Database::getInstance();
$userName = 'admin';
$password = '123456';
$email = 'alissonoliveirasilva.oliveira@gmail.com';
$fullName = 'Alisson Silva';

try {
    $db->exec("DELETE FROM compras WHERE clienteId IN (SELECT id FROM clientes WHERE usuarioId IN (SELECT id FROM usuarios WHERE email = '{$email}'));");
    $db->exec("DELETE FROM produtos WHERE clienteId IN (SELECT id FROM clientes WHERE usuarioId IN (SELECT id FROM usuarios WHERE email = '{$email}'));");

    // 2. Apaga o cliente e o usuário principal
    $db->exec("DELETE FROM clientes WHERE usuarioId IN (SELECT id FROM usuarios WHERE email = '{$email}');");
    $db->exec("DELETE FROM usuarios WHERE email = '{$email}';");
    $usuarioRepo = new UsuariosRepository($db);
    $dados_usuario = [
        'username' => $userName,
        'email' => $email,
        'password' => $password,
        'fullName' => $fullName,
    ];

    $stmt = $db->prepare('SELECT id, fullName FROM usuarios WHERE email = :email');
    $stmt->execute([':email' => $email]);
    $usuarioExistente = $stmt->fetch(PDO::FETCH_ASSOC);

    $id_usuario = null;

    if ($usuarioExistente) {
        $id_usuario = $usuarioExistente['id'];
        echo "Usuário '{$email}' já existe (ID: {$id_usuario}). Reutilizando para FKs.\n";
    } else {
        if ($usuarioRepo->inserir($dados_usuario)) {
            $id_usuario = $db->lastInsertId();
            echo "✅ Usuário Admin inserido com sucesso! (ID: {$id_usuario})\n";
        } else {
            throw new Exception('Falha ao inserir o Usuário Admin.');
        }
    }

    $clienteRepo = new ClientesRepository($db);
    $dados_cliente = [
        'usuarioId' => $id_usuario,
        'nome' => 'Artur Guimarães Oliveira',
        'email' => 'artur@cliente.com.br',
        'telefone' => '83993060292',
        'endereco' => 'Rua dos Clientes, 100',
    ];

    $stmt = $db->prepare('SELECT id FROM clientes WHERE email = :email');
    $stmt->execute([':email' => $dados_cliente['email']]);
    $idClienteExistente = $stmt->fetchColumn();

    $id_cliente = null;

    if ($idClienteExistente) {
        $id_cliente = $idClienteExistente;
        echo "Cliente '{$dados_cliente['email']}' já existe (ID: {$id_cliente}). Reutilizando para FKs.\n";
    } else {
        if ($clienteRepo->inserir($dados_cliente)) {
            $id_cliente = $db->lastInsertId();
            echo "✅ Cliente inserido com sucesso! (ID: {$id_cliente})\n";
        } else {
            throw new Exception('Falha ao inserir o Cliente.');
        }
    }

    // --- 2. INSERIR PRODUTOS (Relacionados ao Cliente - Conforme sua última requisição) ---
    $produtosRepo = new ProdutosRepository($db);

    $produtos_a_inserir = [
        [
            'clienteId' => $id_cliente,
            'nome' => 'Ingresso Curso DEV Evolution',
            'descricao' => 'Ingresso para o curso completo de Desenvolvimento',
            'preco' => 497.0,
            'quantidade' => 50,
        ],
        [
            'clienteId' => $id_cliente,
            'nome' => 'Ingresso Evento Opa Evolution',
            'descricao' => 'Ingresso para o evento presencial Opa Evolution',
            'preco' => 199.0,
            'quantidade' => 25,
        ]
    ];

    $produtos_ids = [];
    foreach ($produtos_a_inserir as $dados_produto) {
        $stmt = $db->prepare('SELECT id FROM produtos WHERE nome = :nome AND clienteId = :clienteId');
        $stmt->execute([':nome' => $dados_produto['nome'], ':clienteId' => $id_cliente]);
        $idProdutoExistente = $stmt->fetchColumn();

        if ($idProdutoExistente) {
            $produtos_ids[$dados_produto['nome']] = $idProdutoExistente;
            echo "Produto '{$dados_produto['nome']}' já existe (ID: {$idProdutoExistente}). Reutilizando.\n";
        } else {
            if ($produtosRepo->inserir($dados_produto)) {
                $id_produto = $db->lastInsertId();
                $produtos_ids[$dados_produto['nome']] = $id_produto;
                echo "✅ Produto '{$dados_produto['nome']}' inserido! (ID: {$id_produto})\n";
            } else {
                throw new Exception("Falha ao inserir o produto: {$dados_produto['nome']}");
            }
        }
    }

    // Seu arquivo de script PHP (Onde você está inserindo os dados)

    // Certifique-se de que os repositórios estão instanciados
    // $db = Database::getInstance();
    // $produtosRepo = new ProdutosRepository($db);
    $comprasRepo = new ComprasRepository($db);

    $produtosRepo = new ProdutosRepository($db);  // Exemplo, ajuste conforme seu código
    $compras_a_inserir = [];

    $preco_ingresso_dev = $produtosRepo->buscarPrecoPorId($produtos_ids['Ingresso Curso DEV Evolution']);
    $preco_ingresso_opa = $produtosRepo->buscarPrecoPorId($produtos_ids['Ingresso Evento Opa Evolution']);

    // --- 3. INSERIR COMPRAS (Relacionadas ao Cliente e aos Produtos) ---

    // Primeira Compra: Ingresso Curso DEV Evolution
    $quantidade_dev = 1;
    $valor_total_dev = $preco_ingresso_dev * $quantidade_dev;

    $compras_a_inserir[] = [
        'clienteId' => $id_cliente,
        'produtoId' => $produtos_ids['Ingresso Curso DEV Evolution'],
        'quantidade' => $quantidade_dev,
        'dataCompra' => date('Y-m-d H:i:s'),
        'valorTotal' => $valor_total_dev,
    ];

    // Segunda Compra: Ingresso Evento Opa Evolution
    $quantidade_opa = 1;
    $valor_total_opa = $preco_ingresso_opa * $quantidade_opa;

    $compras_a_inserir[] = [
        'clienteId' => $id_cliente,
        'produtoId' => $produtos_ids['Ingresso Evento Opa Evolution'],
        'quantidade' => $quantidade_opa,
        'dataCompra' => date('Y-m-d H:i:s'),
        'valorTotal' => $valor_total_opa,
    ];

    $total_compras = 0;
    foreach ($compras_a_inserir as $dados_compra) {
        if ($comprasRepo->inserir($dados_compra)) {
            $id_compra = $db->lastInsertId();
            echo "✅ Compra inserida com sucesso! (ID: {$id_compra})\n";
            $total_compras++;
        } else {
            throw new Exception('Falha ao inserir a Compra.');
        }
    }

    echo "\n --- Seeding Completo! --- \n";
    echo "Um usuário, um cliente, dois produtos e duas compras foram criados ou verificados.\n";
} catch (Exception $e) {
    echo "\n --- ERRO DURANTE O SEEDING --- \n";
    echo 'Erro: ' . $e->getMessage() . "\n";
    exit(1);
}
