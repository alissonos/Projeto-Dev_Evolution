<?php

class Clientes {
    private $id;
    private $nome;
    private $email;
    private $telefone;
    private $endereco;

    public function getId () {
        return $this->id;
    }

    public function getNome () {
        return $this->nome;
    }

    public function setNome ($nome) {
        $this->nome = $nome;
    }

    public function getEmail () {
        return $this->email;
    }

    public function setEmail ($email) {
        $this->email = $email;
    }
    
    public function getTelefone () {
        return $this->telefone;
    }
    
    public function setTelefone ($telefone) {
        $this->telefone = $telefone;
    }

    public function getEndereco () {
        return $this->endereco;
    }

    public function setEndereco ($endereco) {
        $this->endereco = $endereco;
    }
}