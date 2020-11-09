<?php

class DbConn
{
    private static $instance = null;

    private $database = 'opinioww_testapp';
    private $username = 'opinioww_testapp';
    private $password = 'FpZ%1Ofa';
    private $host = 'localhost';
    private $port = '3306';
    private $charset = 'utf8';
    public $pdo;

    function __construct()
    {
        $dsn = "mysql:host=$this->host;dbname=$this->database;charset=$this->charset";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $this->pdo = new PDO($dsn, $this->username, $this->password, $opt);
    }

    public static function getConnection()
    {
        return
            self::$instance === null
                ? self::$instance = new self()
                : self::$instance;
    }
}