<?php

namespace src\Models;

class Usuarios
{
    private $id;
    private $username;
    private $email;
    private $password;
    private $fullName;

    public function __construct(string $username, string $email, string $password, string $fullName)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->fullName = $fullName;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getfullName()
    {
        return $this->fullName;
    }

    public function setfullName($fullName)
    {
        $this->fullName = $fullName;
    }
}
