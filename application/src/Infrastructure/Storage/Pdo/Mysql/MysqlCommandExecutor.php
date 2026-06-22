<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Pdo\Mysql;

use PDO;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Throwable;

class MysqlCommandExecutor
{
    public const ERROR_COULD_NOT_EXECUTE_SQL = "Couldn't execute sql query successfully";

    private readonly PDO $connection;
    private readonly LoggerInterface $logger;

    public function __construct(
        MysqlPdoConnection $pdoConnection,
        LoggerInterface $logger
    ) {
        $this->connection = $pdoConnection->getPDO();
        $this->logger = $logger;
    }

    public function truncateTable(string $tableName): void
    {
        $sqlQuery = "SET foreign_key_checks=0;";
        $sqlQuery .= sprintf('TRUNCATE TABLE %s;', $tableName);
        $sqlQuery .= "SET foreign_key_checks=1";
        $this->executeSql($sqlQuery);
    }

    public function executeSql(string $sql): void
    {
        try {
            $this->connection->exec($sql);
        } catch (Throwable $t) {
            $this->processThrowable($t);
        }
    }

    private function processThrowable(Throwable $t): void
    {
        $this->logger->error(self::ERROR_COULD_NOT_EXECUTE_SQL, [
            'message' => $t->getMessage(),
            'trace' => $t->getTraceAsString()
        ]);
        throw new RuntimeException(self::ERROR_COULD_NOT_EXECUTE_SQL);
    }
}
