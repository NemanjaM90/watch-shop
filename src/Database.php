<?php

namespace WatchShop;

use PDO;
use PDOException;

class Database
{
  private $connection;

  public function __construct()
  {
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();

    $host = $_ENV['DB_HOST'];
    $db   = $_ENV['DB_DATABASE'];
    $user = $_ENV['DB_USERNAME'];
    $pass = $_ENV['DB_PASSWORD'];
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
      $this->connection = new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
      throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
  }

  public function getConnection()
  {
    return $this->connection;
  }
}
