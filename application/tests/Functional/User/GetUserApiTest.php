<?php

namespace App\Tests\Functional\User;

use App\Tests\Abstract\AbstractUsersApiTest;
use Symfony\Component\HttpFoundation\Response;

class GetUserApiTest extends AbstractUsersApiTest
{
    public function testGetSelfUserWithRoleRoot()
    {
        $this->client->jsonRequest(
            'GET',
            sprintf("%s?id=%d", self::USERS_API_URI, $this->userWithRoleRoot->getId()),
            [],
            [ 'HTTP_AUTHORIZATION' => 'Bearer ' . $this->tokenForRoleRoot ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->checkResponseHasUserData(
            self::CREATED_ROOT_LOGIN,
            self::CREATED_ROOT_PASSWORD,
            self::CREATED_ROOT_PHONE
        );
    }

    public function testGetSelfUserWithRoleUser()
    {
        $this->client->jsonRequest(
            'GET',
            sprintf("%s?id=%d", self::USERS_API_URI, $this->userWithRoleUser->getId()),
            [],
            [ 'HTTP_AUTHORIZATION' => 'Bearer ' . $this->tokenForRoleUser ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->checkResponseHasUserData(
            self::CREATED_USER_LOGIN,
            self::CREATED_USER_PASSWORD,
            self::CREATED_USER_PHONE
        );
    }

    public function testGetAnotherUserWithRoleRoot()
    {
        $this->client->jsonRequest(
            'GET',
            sprintf("%s?id=%d", self::USERS_API_URI, $this->userWithRoleUser->getId()),
            [],
            [ 'HTTP_AUTHORIZATION' => 'Bearer ' . $this->tokenForRoleRoot ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->checkResponseHasUserData(
            self::CREATED_USER_LOGIN,
            self::CREATED_USER_PASSWORD,
            self::CREATED_USER_PHONE
        );
    }

    public function testGetAnotherUserWithRoleUser()
    {
        $this->client->jsonRequest(
            'GET',
            sprintf("%s?id=%d", self::USERS_API_URI, $this->userWithRoleRoot->getId()),
            [],
            [ 'HTTP_AUTHORIZATION' => 'Bearer ' . $this->tokenForRoleUser ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
