<?php

namespace App\Tests\Functional\Auth;

use App\Domain\Entity\User;
use App\Domain\ValueObject\Role\RoleName;
use App\Tests\Abstract\AbstractApiTest;
use Symfony\Component\HttpFoundation\Response;

class GetTokenTest extends AbstractApiTest
{
    public const USERS_AUTH_TOKEN_URI = 'v1/api/auth/token';
    public const USER_LOGIN = 'user1';
    public const USER_PASSWORD_CORRECT = 'correct';
    public const USER_PASSWORD_WRONG = 'wrong';
    public const USER_PHONE = 'phone';

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUserInDatabase(
            self::USER_LOGIN,
            self::USER_PASSWORD_CORRECT,
            self::USER_PHONE,
            RoleName::ROLE_NAME_USER
        );
    }

    public function testGetTokenSuccessfully()
    {
        $this->client->jsonRequest(
            'POST',
            self::USERS_AUTH_TOKEN_URI,
            [
                'login' => self::USER_LOGIN,
                'pass' => self::USER_PASSWORD_CORRECT
            ]
        );
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertArrayHasKey('token', $responseData);
    }

    public function testGetTokenWithWrongPassword()
    {
        $this->client->jsonRequest(
            'POST',
            self::USERS_AUTH_TOKEN_URI,
            [
                'login' => self::USER_LOGIN,
                'pass' => self::USER_PASSWORD_WRONG
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}
