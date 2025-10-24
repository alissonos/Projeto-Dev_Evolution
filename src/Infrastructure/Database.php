<?php

namespace src\Infrastructure;

class Database
{
    private static $instance = null;

    public static function getInstance(): \PDO
    {
        if (self::$instance === null) {
            self::connect();
        }
        return self::$instance;
    }

    private static function connect()
    {
        $rootPath = dirname(__DIR__, 2);

        $dbFileName = 'dev_evolution.sqlite';

        $dbPath = $rootPath . DIRECTORY_SEPARATOR . $dbFileName;

        $dsn = 'sqlite:' . $dbPath;

        try {
            self::$instance = new \PDO($dsn);
            self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new \PDOException('Erro na conexão: ' . $e->getMessage() . ' - Caminho tentado: ' . $dbPath);
        }
    }
}
