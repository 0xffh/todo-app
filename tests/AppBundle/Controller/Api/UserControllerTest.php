<?php

namespace Tests\AppBundle\Controller\Api;


class UserControllerTest extends ApiTestCase
{
    public function testCreateUser()
    {
        $response = parent::$client->post(
            '/api/users',
            [
                'form_params' => [
                    'user[username]'              => 'admin',
                    'user[plainPassword][first]'  => '12345678',
                    'user[plainPassword][second]' => '12345678',
                ],
            ]
        );
    
        $this->assertEquals(201, $response->getStatusCode());
    
        $responseContent = $response->getBody()->getContents();
        $responseJSONContent = json_decode($responseContent, true);
    
        $this->assertArrayHasKey('id', $responseJSONContent);
        $this->assertArrayHasKey('username', $responseJSONContent);
        $this->assertEquals('2', $responseJSONContent['id']);
        $this->assertEquals('admin', $responseJSONContent['username']);
    }
}