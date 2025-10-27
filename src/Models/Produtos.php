<?php

namespace src\Models;

class Produtos
{
    private $id;
    private $nome;
    private int $clienteId;
    private $descricao;
    private $preco;
    private $quantidade;

    public function getId()
    {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getClienteId(): int
    {
        return $this->clienteId;
    }

    public function setClienteId(int $clienteId): void
    {
        $this->clienteId = $clienteId;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function getPreco()
    {
        return $this->preco;
    }

    public function setPreco($preco)
    {
        $this->preco = $preco;
    }

    public function getQuantidade()
    {
        return $this->quantidade;
    }

    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }
}
