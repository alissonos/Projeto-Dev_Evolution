<?php
require_once 'vendor/autoload.php';
require_once 'src/Infrastructure/Database.php';
require_once 'src/Infrastructure/ProdutosRepository.php';

use src\Infrastructure\Database;
use src\Infrastructure\ProdutosRepository;

$db = Database::getInstance();
$produtosRepo = new ProdutosRepository($db);

$produtos_para_inserir = [
    [
        'nome' => 'Ingresso Curso DEV Evolution',
        'descricao' => 'Ingresso para o curso completo de Desenvolvimento',
        'preco' => 497.00,
        'quantidade' => 50,
    ],
    [
        'nome' => 'Ingresso Evento Opa Evolution',
        'descricao' => 'Ingresso para o evento presencial Opa Evolution',
        'preco' => 199.00,
        'quantidade' => 25,
    ]
];

echo "Iniciando a inserção de produtos...\n";

foreach ($produtos_para_inserir as $dados) {
    if ($produtosRepo->inserir($dados)) {
        echo "✅ Produto '{$dados['nome']}' inserido com sucesso!\n";
    } else {
        echo "❌ Falha ao inserir o produto: {$dados['nome']}\n";
    }
}

echo "Finalizado. Agora o catálogo está cheio.\n";
