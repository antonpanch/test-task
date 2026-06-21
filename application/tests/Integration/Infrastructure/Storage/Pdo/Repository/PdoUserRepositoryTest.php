<?php

namespace App\Tests\Integration\Infrastructure\Storage\Pdo\Repository;

use App\Application\Password\EncryptedPasswordStrategy;
use App\Application\Password\HashedPasswordStrategy;
use App\Application\Password\PasswordStrategyInterface;
use App\Application\Password\PlainPasswordStrategy;
use App\Application\User\CreateUserUseCase;
use App\Domain\Entity\Role;
use App\Domain\Entity\User;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\ValueObject\Role\RoleName;
use App\Domain\ValueObject\User\Login;
use App\Domain\ValueObject\User\Password;
use App\Domain\ValueObject\User\PhoneNumber;
use App\Domain\ValueObject\User\UserId;
use App\Infrastructure\Storage\Pdo\Connection\PdoConnectionInterface;
use App\Infrastructure\Storage\Pdo\Mysql\MysqlCommandExecutor;
use App\Infrastructure\Storage\Pdo\Repository\PdoRoleRepository;
use App\Infrastructure\Storage\Pdo\Repository\PdoUserRepository;
use App\Tests\Service\DatabaseService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class PdoUserRepositoryTest extends KernelTestCase
{
    private const FIRST_USER_FROM_FILE = [
        'id' => 2,
        'login' => 'user1',
        'pass' => 'userpass',
        'phone' => 'phone2',
        'roleName' => RoleName::ROLE_NAME_USER
    ];

    private const NEW_USER_LOGIN = 'login3';
    private const NEW_USER_PASSWORD = 'pass4';
    private const NEW_USER_PHONE = 'phone4';

    private Role $userRole;

    protected function setUp(): void
    {
        self::bootKernel();
        /** @var PdoRoleRepository $pdoRoleRepository */
        $pdoRoleRepository = $this->getContainer()->get(PdoRoleRepository::class);
        $this->userRole = $pdoRoleRepository->getByName(new RoleName(RoleName::ROLE_NAME_USER));
    }

    public static function getPasswordStrategies(): array
    {
        $container = self::getContainer();
        return [
            [$container->get(EncryptedPasswordStrategy::class)],
            [$container->get(HashedPasswordStrategy::class)],
            [$container->get(PlainPasswordStrategy::class)]
        ];
    }

    public function getPdoUserRepository(PasswordStrategyInterface $passwordStrategy): PdoUserRepository
    {
        $container = $this->getContainer();
        $mysqlCommandExecutor = $container->get(MysqlCommandExecutor::class);
        $createUserUseCase = $container->get(CreateUserUseCase::class);
        $pdoConnection = $container->get(PdoConnectionInterface::class);
        $databaseService = new DatabaseService(
            $mysqlCommandExecutor,
            $createUserUseCase,
            $passwordStrategy
        );
        $databaseService->dropTables();
        $databaseService->createTables();
        $databaseService->createUsersFromFile();
        return new PdoUserRepository($pdoConnection, $passwordStrategy);
    }

    #[DataProvider('getPasswordStrategies')]
    public function testCreate(PasswordStrategyInterface $passwordStrategy)
    {
        $pdoUserRepository = $this->getPdoUserRepository($passwordStrategy);
        $newUser = new User(
            new Login(self::NEW_USER_LOGIN),
            new Password(self::NEW_USER_PASSWORD),
            new PhoneNumber(self::NEW_USER_PHONE),
            $this->userRole
        );
        $user = $pdoUserRepository->create($newUser);
        $this->assertNotNull($user->getId());
    }

    #[DataProvider('getPasswordStrategies')]
    public function testFindByUserAndPassword(PasswordStrategyInterface $passwordStrategy)
    {
        $pdoUserRepository = $this->getPdoUserRepository($passwordStrategy);
        $user = $pdoUserRepository->findByLoginAndPassword(
            new Login(self::FIRST_USER_FROM_FILE['login']),
            new Password(self::FIRST_USER_FROM_FILE['pass'])
        );
        $this->assertEquals(self::FIRST_USER_FROM_FILE['phone'], $user->getPhoneNumber());
    }

    #[DataProvider('getPasswordStrategies')]
    public function testFindById(PasswordStrategyInterface $passwordStrategy)
    {
        $pdoUserRepository = $this->getPdoUserRepository($passwordStrategy);
        $user = $pdoUserRepository->findById(new UserId(self::FIRST_USER_FROM_FILE['id']));
        $this->assertEquals(self::FIRST_USER_FROM_FILE['login'], $user->getLogin());
    }

    #[DataProvider('getPasswordStrategies')]
    public function testDelete(PasswordStrategyInterface $passwordStrategy)
    {
        $pdoUserRepository = $this->getPdoUserRepository($passwordStrategy);
        $pdoUserRepository->delete(new UserId(self::FIRST_USER_FROM_FILE['id']));
        $this->expectException(EntityNotFoundException::class);
        $pdoUserRepository->findById(new UserId(self::FIRST_USER_FROM_FILE['id']));
    }

    #[DataProvider('getPasswordStrategies')]
    public function testUpdate(PasswordStrategyInterface $passwordStrategy)
    {
        $pdoUserRepository = $this->getPdoUserRepository($passwordStrategy);
        $pdoUserRepository->update(
            new UserId(self::FIRST_USER_FROM_FILE['id']),
            new Login(self::NEW_USER_LOGIN),
            new Password(self::NEW_USER_PASSWORD),
            new PhoneNumber(self::NEW_USER_PHONE)
        );
        $user = $pdoUserRepository->findById(new UserId(self::FIRST_USER_FROM_FILE['id']));
        $this->assertEquals(self::NEW_USER_LOGIN, $user->getLogin());
        $this->assertEquals(self::NEW_USER_PHONE, $user->getPhoneNumber());
    }
}
