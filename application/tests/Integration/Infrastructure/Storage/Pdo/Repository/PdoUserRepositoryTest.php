<?php

namespace App\Tests\Integration\Infrastructure\Storage\Pdo\Repository;

use App\Application\User\CreateUserUseCase;
use App\Domain\Entity\Role;
use App\Domain\Entity\User;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\ValueObject\Role\RoleId;
use App\Domain\ValueObject\Role\RoleName;
use App\Domain\ValueObject\User\Login;
use App\Domain\ValueObject\User\Password;
use App\Domain\ValueObject\User\PhoneNumber;
use App\Domain\ValueObject\User\UserId;
use App\Infrastructure\Storage\Pdo\Mysql\MysqlCommandExecutor;
use App\Infrastructure\Storage\Pdo\Repository\PdoUserRepository;
use App\Tests\Service\DatabaseService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PdoUserRepositoryTest extends KernelTestCase
{
    private const INTEGRATION_TEST_SQL_FILENAME = '/application/tests/dump/integration.sql';

    private const EXISTING_USER_ID_1 = 2;
    private const EXISTING_USER_LOGIN_1 = 'user1';
    private const EXISTING_USER_PASSWORD_1 = 'pass_2';
    private const EXISTING_USER_PHONE_1 = 'phone_2';

    private const NEW_USER_LOGIN = 'login3';
    private const NEW_USER_PASSWORD = 'pass_4';
    private const NEW_USER_PHONE = 'phone4';

    private const ROOT_ROLE_ID = 1;
    private const USER_ROLE_ID = 2;

    private PdoUserRepository $pdoUserRepository;
    private readonly DatabaseService $databaseService;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->pdoUserRepository = $this->getContainer()->get(PdoUserRepository::class);
        $this->databaseService = new DatabaseService(
            $this->getContainer()->get(MysqlCommandExecutor::class),
            $this->getContainer()->get(CreateUserUseCase::class)
        );
        $this->databaseService->dropTables();
        $this->databaseService->createTables();
        $this->databaseService->truncateTables(['tokens', 'users']);
        $this->databaseService->executeSqlFromFile(self::INTEGRATION_TEST_SQL_FILENAME);
    }

    public function testCreate()
    {
        $newUser = new User(
            new Login(self::NEW_USER_LOGIN),
            new Password(self::NEW_USER_PASSWORD),
            new PhoneNumber(self::NEW_USER_PHONE),
            new Role(
                new RoleName(RoleName::ROLE_NAME_USER),
                new RoleId(self::USER_ROLE_ID)
            )
        );
        $user = $this->pdoUserRepository->create($newUser);
        $this->assertNotNull($user->getId());
    }

    public function testFindByUserAndPassword()
    {
        $user = $this->pdoUserRepository->findByLoginAndPassword(
            new Login(self::EXISTING_USER_LOGIN_1),
            new Password(self::EXISTING_USER_PASSWORD_1)
        );
        $this->assertEquals(self::EXISTING_USER_PHONE_1, $user->getPhoneNumber());
    }

    public function testFindById()
    {
        $user = $this->pdoUserRepository->findById(new UserId(self::EXISTING_USER_ID_1));
        $this->assertEquals(self::EXISTING_USER_LOGIN_1, $user->getLogin());
    }

    public function testDelete()
    {
        $this->pdoUserRepository->delete(new UserId(self::EXISTING_USER_ID_1));
        $this->expectException(EntityNotFoundException::class);
        $this->pdoUserRepository->findById(new UserId(self::EXISTING_USER_ID_1));
    }

    public function testUpdate()
    {
        $this->pdoUserRepository->update(
            new UserId(self::EXISTING_USER_ID_1),
            new Login(self::NEW_USER_LOGIN),
            new Password(self::NEW_USER_PASSWORD),
            new PhoneNumber(self::NEW_USER_PHONE)
        );
        $user = $this->pdoUserRepository->findById(new UserId(self::EXISTING_USER_ID_1));
        $this->assertEquals(self::NEW_USER_LOGIN, $user->getLogin());
        $this->assertEquals(self::NEW_USER_PASSWORD, $user->getPassword());
        $this->assertEquals(self::NEW_USER_PHONE, $user->getPhoneNumber());
    }

    public function createUserInDatabase(string $login, string $password, string $phone, string $roleName): User
    {
        return $this->databaseService->createUserInDatabase($login, $password, $phone, $roleName);
    }
}
