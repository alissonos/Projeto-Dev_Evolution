<?php

class Compras
{
    private $id;
    private $nomeComprador;
    private $clienteId;
    private $produtoId;
    private $quantidade;
    private $dataCompra;

    public function getId()
    {
        return $this->id;
    }

    public function getNomeComprador()
    {
        return $this->nomeComprador;
    }

    public function setNomeComprador($nomeComprador)
    {
        $this->nomeComprador = $nomeComprador;
    }

    public function getClienteId()
    {
        return $this->clienteId;
    }

    public function setClienteId($clienteId)
    {
        $this->clienteId = $clienteId;
    }

    public function getProdutoId()
    {
        return $this->produtoId;
    }

    public function setProdutoId($produtoId)
    {
        $this->produtoId = $produtoId;
    }

    public function getQuantidade()
    {
        return $this->quantidade;
    }

    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    public function getDataCompra()
    {
        return $this->dataCompra;
    }

    public function setDataCompra($dataCompra)
    {
        $this->dataCompra = $dataCompra;
    }
}
