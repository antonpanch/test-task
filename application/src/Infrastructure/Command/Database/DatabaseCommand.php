<?php

namespace App\Infrastructure\Command\Database;

use App\Application\Password\EncryptedPasswordStrategy;
use App\Application\Password\HashedPasswordStrategy;
use App\Application\Password\PasswordStrategyInterface;
use App\Application\Password\PlainPasswordStrategy;
use App\Infrastructure\Storage\Pdo\Mysql\MysqlCommandExecutor;
use InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseCommand
{
    public const CREATE_TABLES_AND_ROLES_FILE = "/application/dump/create_tables_with_roles.sql";
    public const FILE_CREATE_TOKENS = "/application/dump/create_tokens.sql";

    public const FILE_NAMES_BY_PASSWORD_STRATEGY = [
        PasswordStrategyInterface::STRATEGY_PLAIN => "/application/dump/create_users_with_plain_password.sql",
        PasswordStrategyInterface::STRATEGY_HASHED => "/application/dump/create_users_with_hashed_password.sql",
        PasswordStrategyInterface::STRATEGY_ENCRYPTED => "/application/dump/create_users_with_encrypted_password.sql"
    ];

    public function __construct(
        private readonly MysqlCommandExecutor $mysqlCommandExecutor,
        private readonly PasswordStrategyInterface $passwordStrategy
    ) {
    }

    #[AsCommand(name: 'db:create-tables', description: 'Create tables in database')]
    public function createTablesAndRoles(OutputInterface $output): int
    {
        $this->executeSqlFromFile(self::CREATE_TABLES_AND_ROLES_FILE);
        $output->writeln("Tables were successfully created");
        return Command::SUCCESS;
    }

    #[AsCommand(name: 'db:create-test-data', description: "Add test users and tokens to tables in database")]
    public function createTestData(OutputInterface $output): int
    {
        $createUsersFile = $this->getFileNameToCreateUsers();
        $this->executeSqlFromFile($createUsersFile);
        $this->executeSqlFromFile(self::FILE_CREATE_TOKENS);
        $output->writeln("Test users and tokens were successfully added");
        return Command::SUCCESS;
    }

    #[AsCommand(name: 'db:clear-users-and-tokens-tables', description: "Clear users and tokens table")]
    public function clearUsersAndTokensTables(OutputInterface $output): int
    {
        $this->mysqlCommandExecutor->truncateTable('tokens');
        $this->mysqlCommandExecutor->truncateTable('users');
        $output->writeln("Tables were cleared successfully");
        return Command::SUCCESS;
    }

    private function executeSqlFromFile(string $file)
    {
        $sql = file_get_contents($file);
        $this->mysqlCommandExecutor->executeSql($sql);
    }

    private function getFileNameToCreateUsers(): string
    {
        $passwordStrategyName = $this->passwordStrategy->getStrategyName();
        if (!array_key_exists($passwordStrategyName, self::FILE_NAMES_BY_PASSWORD_STRATEGY)) {
            throw new InvalidArgumentException("Wrong password strategy provided");
        }
        return self::FILE_NAMES_BY_PASSWORD_STRATEGY[$passwordStrategyName];
    }
}
