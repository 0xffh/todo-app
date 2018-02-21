<?php

namespace Tests\AppBundle\Controller\Api;


class AuthControllerTest extends ApiTestCase
{
    public function testLogin()
    {
        $response = parent::$client->post(
            '/api/login_check',
            [
                'form_params' => [
                    '_username' => 'user-one',
                    '_password' => 'qwerty123',
                ],
            ]
        );

        $this->assertEquals(200, $response->getStatusCode());

        $responseContent = $response->getBody()->getContents();

        $responseJSONContent = json_decode($responseContent, true);

        $this->assertArrayHasKey('token', $responseJSONContent);
    }
}