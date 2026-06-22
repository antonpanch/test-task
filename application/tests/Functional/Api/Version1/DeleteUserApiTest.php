<?php

namespace App\Tests\Functional\Api\Version1;

use App\Tests\Abstract\AbstractUsersApiTest;
use Symfony\Component\HttpFoundation\Response;

class DeleteUserApiTest extends AbstractUsersApiTest
{
    protected const USERS_API_URI = '/v1/api/users';

    public function testDeleteUserAsRoot()
    {
        $this->client->jsonRequest(
            'DELETE',
            sprintf("%s?id=%d", self::USERS_API_URI, $this->userWithRoleUser->getId()),
            [],
            [ 'HTTP_AUTHORIZATION' => 'Bearer ' . $this->tokenForRoleRoot ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testDeleteUserAsUser()
    {
        $this->client->jsonRequest(
            'DELETE',
            sprintf("%s?id=%d", self::USERS_API_URI, $this->userWithRoleRoot->getId()),
            [],
            [ 'HTTP_AUTHORIZATION' => 'Bearer ' . $this->tokenForRoleUser ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
