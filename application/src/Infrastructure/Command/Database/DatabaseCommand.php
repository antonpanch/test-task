<?php

namespace App\Infrastructure\Command\Database;

use App\Infrastructure\Storage\Pdo\Mysql\MysqlCommandExecutor;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseCommand
{
    public const CREATE_TABLES_AND_ROLES_FILE = "/application/dump/create_tables_with_roles.sql";
    public const CREATE_TEST_DATA_FILE = "/application/dump/create_test_users_and_tokens.sql";

    public function __construct(private readonly MysqlCommandExecutor $mysqlCommandExecutor)
    {
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
        $this->executeSqlFromFile(self::CREATE_TEST_DATA_FILE);
        $output->writeln("Test data was successfully added");
        return Command::SUCCESS;
    }

    private function executeSqlFromFile(string $file)
    {
        $sql = file_get_contents($file);
        $this->mysqlCommandExecutor->executeSql($sql);
    }
}
