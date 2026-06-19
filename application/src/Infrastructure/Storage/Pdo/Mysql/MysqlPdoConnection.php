<?php

namespace App\Infrastructure\Storage\Pdo\Mysql;

use App\Infrastructure\Storage\Pdo\Connection\PdoConnectionInterface;
use PDO;

class MysqlPdoConnection implements PdoConnectionInterface
{
    private PDO $connection;

    public function __construct(string $host, int $port, string $databaseName, string $userName, string $password)
    {
        $dsn = sprintf("mysql:host=%s;port=%d;dbname=%s", $host, $port, $databaseName);
        $this->connection = new PDO($dsn, $userName, $password);
    }

    public function getPDO(): PDO
    {
        return $this->connection;
    }
}
