<?php

namespace App\Tests\Functional\Api\Version2;

use App\Tests\Abstract\AbstractUsersApiTest;
use Symfony\Component\HttpFoundation\Response;

class GetUsersApiTest extends AbstractUsersApiTest
{
    protected const USERS_API_URI = '/api/v2/users';

    public function testGetUsersWithRoleRoot()
    {
        $this->client->jsonRequest(
            'GET',
            self::USERS_API_URI,
            [],
            [ 'HTTP_AUTHORIZATION' => 'Bearer ' . $this->tokenForRoleRoot ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $fields = $this->getFieldsFromResponse();
        $this->assertArrayHasKey('data', $fields);
        $this->assertEquals(2, count($fields['data']));
    }

    public function testGetUsersWithRoleUser()
    {
        $this->client->jsonRequest(
            'GET',
            self::USERS_API_URI,
            [],
            [ 'HTTP_AUTHORIZATION' => 'Bearer ' . $this->tokenForRoleUser ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $fields = $this->getFieldsFromResponse();
        $this->assertArrayHasKey('data', $fields);
        $this->assertEquals(1, count($fields['data']));
        $this->assertEquals(self::CREATED_USER_LOGIN, $fields['data'][0]['login']);
    }

    public function testGetUsersWithRoleRootWithPages()
    {
        $this->client->jsonRequest(
            'GET',
            sprintf("%s?perPage=%d", self::USERS_API_URI, 1),
            [],
            [ 'HTTP_AUTHORIZATION' => 'Bearer ' . $this->tokenForRoleRoot ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $fields = $this->getFieldsFromResponse();
        $this->assertArrayHasKey('data', $fields);
        $this->assertEquals(1, count($fields['data']));
        $this->assertEquals(self::CREATED_ROOT_LOGIN, $fields['data'][0]['login']);

        $afterId = $fields['data'][0]['id'];
        $this->client->jsonRequest(
            'GET',
            sprintf("%s?perPage=%d&afterId=%d", self::USERS_API_URI, 1, $afterId),
            [],
            [ 'HTTP_AUTHORIZATION' => 'Bearer ' . $this->tokenForRoleRoot ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $fields = $this->getFieldsFromResponse();
        $this->assertArrayHasKey('data', $fields);
        $this->assertEquals(1, count($fields['data']));
        $this->assertEquals(self::CREATED_USER_LOGIN, $fields['data'][0]['login']);
    }
}
