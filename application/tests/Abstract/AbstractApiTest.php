<?php

namespace App\Tests\Abstract;

use App\Application\User\CreateUserUseCase;
use App\Domain\Entity\User;
use App\Infrastructure\Storage\Pdo\Mysql\MysqlCommandExecutor;
use App\Tests\Service\DatabaseService;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbstractApiTest extends WebTestCase
{
    protected KernelBrowser $client;
    protected readonly DatabaseService $databaseService;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->databaseService = new DatabaseService(
            $this->getContainer()->get(MysqlCommandExecutor::class),
            $this->getContainer()->get(CreateUserUseCase::class)
        );
        $this->databaseService->dropTables();
        $this->databaseService->createTables();
        $this->databaseService->truncateTables(['tokens', 'users']);
    }

    protected function createUserInDatabase(string $login, string $password, string $phone, string $roleName): User
    {

        return $this->databaseService->createUserInDatabase($login, $password, $phone, $roleName);
    }

    protected function getFieldsFromResponse(): array
    {
        return json_decode($this->client->getResponse()->getContent(), true);
    }
}
