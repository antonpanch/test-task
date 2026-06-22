<?php

namespace App\Tests\Service;

use App\Application\Password\PasswordStrategyInterface;
use App\Application\User\CreateUserUseCase;
use App\Domain\Entity\User;
use App\Domain\ValueObject\User\Login;
use App\Domain\ValueObject\User\Password;
use App\Domain\ValueObject\User\PhoneNumber;
use App\Infrastructure\Storage\Pdo\Mysql\MysqlCommandExecutor;
use InvalidArgumentException;

class DatabaseService
{
    protected const CREATE_TABLES_SQL_FILENAME = '/application/dump/create_tables_with_roles.sql';
    public const FILE_NAMES_BY_PASSWORD_STRATEGY = [
        PasswordStrategyInterface::STRATEGY_PLAIN => "/application/tests/dump/create_users_with_plain_password.sql",
        PasswordStrategyInterface::STRATEGY_HASHED => "/application/tests/dump/create_users_with_hashed_password.sql",
        PasswordStrategyInterface::STRATEGY_ENCRYPTED =>
            "/application/tests/dump/create_users_with_encrypted_password.sql"
    ];

    public function __construct(
        private readonly MysqlCommandExecutor $mysqlCommandExecutor,
        private readonly CreateUserUseCase $createUserUseCase,
        private readonly PasswordStrategyInterface $passwordStrategy
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

    public function executeSqlFromFile(string $file): void
    {
        $sql = file_get_contents($file);
        if ($sql === false) {
            throw new InvalidArgumentException("Couldn't get sql from file");
        }
        $this->mysqlCommandExecutor->executeSql($sql);
    }

    private function getCreateUsersSqlFilename(): string
    {
        $passwordStrategyName = $this->passwordStrategy->getStrategyName();
        if (!array_key_exists($passwordStrategyName, self::FILE_NAMES_BY_PASSWORD_STRATEGY)) {
            throw new InvalidArgumentException('Wrong password strategy provided');
        }
        return self::FILE_NAMES_BY_PASSWORD_STRATEGY[$passwordStrategyName];
    }

    public function createUsersFromFile(): void
    {
        $this->executeSqlFromFile($this->getCreateUsersSqlFilename());
    }
}
