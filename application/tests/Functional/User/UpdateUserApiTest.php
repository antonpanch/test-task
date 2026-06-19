<?php

namespace App\Tests\Functional\User;

use App\Tests\Abstract\AbstractUsersApiTest;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserApiTest extends AbstractUsersApiTest
{
    public function testUpdateSelfUserWithRoleRoot()
    {
        $this->client->jsonRequest(
            'PUT',
            sprintf("%s?id=%d", self::USERS_API_URI, $this->userWithRoleRoot->getId()),
            [
                'login' => self::USER_LOGIN_2,
                'pass' => self::USER_PASSWORD_2,
                'phone' => self::USER_PHONE_2,
            ],
            [ 'HTTP_AUTHORIZATION' => 'Bearer ' . $this->tokenForRoleRoot ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->checkResponseHasUserData(
            self::USER_LOGIN_2,
            self::USER_PASSWORD_2,
            self::USER_PHONE_2
        );
    }

    public function testUpdateSelfUserWithRoleUser()
    {
        $this->client->jsonRequest(
            'PUT',
            sprintf("%s?id=%d", self::USERS_API_URI, $this->userWithRoleUser->getId()),
            [
                'login' => self::USER_LOGIN_2,
                'pass' => self::USER_PASSWORD_2,
                'phone' => self::USER_PHONE_2,
            ],
            [ 'HTTP_AUTHORIZATION' => 'Bearer ' . $this->tokenForRoleUser ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->checkResponseHasUserData(
            self::USER_LOGIN_2,
            self::USER_PASSWORD_2,
            self::USER_PHONE_2
        );
    }

    public function testUpdateAnotherUserWithRoleRoot()
    {
        $this->client->jsonRequest(
            'PUT',
            sprintf("%s?id=%d", self::USERS_API_URI, $this->userWithRoleUser->getId()),
            [
                'login' => self::USER_LOGIN_2,
                'pass' => self::USER_PASSWORD_2,
                'phone' => self::USER_PHONE_2,
            ],
            [ 'HTTP_AUTHORIZATION' => 'Bearer ' . $this->tokenForRoleRoot ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->checkResponseHasUserData(
            self::USER_LOGIN_2,
            self::USER_PASSWORD_2,
            self::USER_PHONE_2
        );
    }

    public function testUpdateAnotherUserWithRoleUser()
    {
        $this->client->jsonRequest(
            'PUT',
            sprintf("%s?id=%d", self::USERS_API_URI, $this->userWithRoleRoot->getId()),
            [
                'login' => 'login0',
                'pass' => 'pass0',
                'phone' => 'phone0',
            ],
            [ 'HTTP_AUTHORIZATION' => 'Bearer ' . $this->tokenForRoleUser ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
