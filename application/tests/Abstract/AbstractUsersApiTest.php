<?php

namespace App\Tests\Abstract;

use App\Application\Authorization\AuthService;
use App\Domain\Entity\User;
use App\Domain\ValueObject\Role\RoleName;

abstract class AbstractUsersApiTest extends AbstractApiTest
{
    protected const USERS_API_URI = '/v1/api/users';

    protected const CREATED_ROOT_LOGIN = 'root_1';
    protected const CREATED_ROOT_PASSWORD = 'pass_1';
    protected const CREATED_ROOT_PHONE = 'phone1';

    protected const CREATED_USER_LOGIN = 'user_1';
    protected const CREATED_USER_PASSWORD = 'pass_1';
    protected const CREATED_USER_PHONE = 'phone_1';

    protected const USER_LOGIN_2 = 'user_2';
    protected const USER_PASSWORD_2 = 'pass_2';
    protected const USER_PHONE_2 = 'phone_2';

    protected AuthService $authService;

    protected User $userWithRoleRoot;
    protected User $userWithRoleUser;

    protected string $tokenForRoleRoot;
    protected string $tokenForRoleUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authService = $this->getContainer()->get(AuthService::class);

        $this->userWithRoleRoot = $this->createUserInDatabase(
            self::CREATED_ROOT_LOGIN,
            self::CREATED_ROOT_PASSWORD,
            self::CREATED_ROOT_PHONE,
            RoleName::ROLE_NAME_ROOT
        );
        $bearerTokenForRoot = $this->authService->generateBearerTokenForUser($this->userWithRoleRoot);
        $this->tokenForRoleRoot = $bearerTokenForRoot->getToken();

        $this->userWithRoleUser = $this->createUserInDatabase(
            self::CREATED_USER_LOGIN,
            self::CREATED_USER_PASSWORD,
            self::CREATED_USER_PHONE,
            RoleName::ROLE_NAME_USER
        );
        $bearerTokenForUser = $this->authService->generateBearerTokenForUser($this->userWithRoleUser);
        $this->tokenForRoleUser = $bearerTokenForUser->getToken();
    }

    protected function checkResponseHasUserData(
        string $login,
        string $password,
        string $phone,
        ?int $id = null
    ) {
        $data = $this->getFieldsFromResponse();
        $this->assertEquals(4, count($data));

        $this->assertEquals($login, $data['login']);
        $this->assertEquals($password, $data['pass']);
        $this->assertEquals($phone, $data['phone']);
        if ($id) {
            $this->assertEquals($id, $data['id']);
            return;
        }
        $this->assertArrayHasKey('id', $data);
    }
}
