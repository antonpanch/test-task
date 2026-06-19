<?php

namespace App\Tests\Service;

use App\Application\User\CreateUserUseCase;
use App\Domain\Entity\User;
use App\Domain\ValueObject\User\Login;
use App\Domain\ValueObject\User\Password;
use App\Domain\ValueObject\User\PhoneNumber;
use App\Infrastructure\Storage\Pdo\Mysql\MysqlCommandExecutor;

class DatabaseService
{
    protected const CREATE_TABLES_SQL_FILENAME = '/application/dump/create_tables_with_roles.sql';

    public function __construct(
        private readonly MysqlCommandExecutor $mysqlCommandExecutor,
        private readonly CreateUserUseCase $createUserUseCase
    ) {
    }

    public function truncateTables(array $tableNames): void
    {
        foreach ($tableNames as $tableName) {
            $this->mysqlCommandExecutor->truncateTable($tableName);
        }
    }

    public function createTables(): void
    {
        $this->executeSqlFromFile(self::CREATE_TABLES_SQL_FILENAME);
    }

    public function dropTables(): void
    {
        $tables = ['tokens', 'users', 'roles'];
        foreach ($tables as $table) {
            $query = sprintf("DROP TABLE IF EXISTS %s", $table);
            $this->mysqlCommandExecutor->executeSql($query);
        }
    }

    public function createUserInDatabase(
        string $login,
        string $password,
        string $phone,
        string $roleName
    ): User {
        return $this->createUserUseCase->handle(
            new Login($login),
            new Password($password),
            new PhoneNumber($phone),
            $roleName
        );
    }

    public function executeSqlFromFile(string $file)
    {
        $sql = file_get_contents($file);
        $this->mysqlCommandExecutor->executeSql($sql);
    }
}
