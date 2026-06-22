<?php

namespace App\Tests\Functional\Api\Version1;

use App\Tests\Abstract\AbstractUsersApiTest;
use Symfony\Component\HttpFoundation\Response;

class CreateUserApiTest extends AbstractUsersApiTest
{
    protected const USERS_API_URI = '/v1/api/users';

    public function testCreateUserWithRootRole()
    {
        $this->client->jsonRequest(
            'POST',
            self::USERS_API_URI,
            [
                'login' => self::USER_LOGIN_2,
                'pass' => self::USER_PASSWORD_2,
                'phone' => self::USER_PHONE_2
            ],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->tokenForRoleRoot
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->checkResponseHasUserData(
            self::USER_LOGIN_2,
            self::USER_PASSWORD_2,
            self::USER_PHONE_2
        );
    }

    public function testCreateUserWithUserRole()
    {
        $this->client->jsonRequest(
            'POST',
            self::USERS_API_URI,
            [
                'login' => self::USER_LOGIN_2,
                'pass' => self::USER_PASSWORD_2,
                'phone' => self::USER_PHONE_2
            ],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->tokenForRoleUser
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->checkResponseHasUserData(
            self::USER_LOGIN_2,
            self::USER_PASSWORD_2,
            self::USER_PHONE_2
        );
    }

    public function testCreateUserWithEmptyLogin()
    {
        $this->client->jsonRequest(
            'POST',
            self::USERS_API_URI,
            [
                'login' => '',
                'pass' => self::USER_PASSWORD_2,
                'phone' => self::USER_PHONE_2
            ],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->tokenForRoleUser
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertArrayHasKey('error', $this->getFieldsFromResponse());
    }
}
