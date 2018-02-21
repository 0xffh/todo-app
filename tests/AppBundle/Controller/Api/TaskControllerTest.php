<?php

namespace Tests\AppBundle\Controller\Api;


use GuzzleHttp\Exception\ClientException;

class TaskControllerTest extends ApiTestCase
{
    public function testGetAllTasks()
    {
        $bearerToken = parent::getBearerToken();
        $response = parent::$client->get(
            '/api/tasks',
            [
                'headers' => [
                    'Authorization' => sprintf('Bearer %s', $bearerToken),
                ],
            ]
        );
        $this->assertEquals(200, $response->getStatusCode());
        
        $responseContent = $response->getBody()->getContents();
        
        $responseJSONContent = json_decode($responseContent, true);
        
        $this->assertEquals(2, count($responseJSONContent));
    }
    
    public function testGetAllTasksWithoutAuthorization()
    {
        $this->setExpectedException(ClientException::class);
    
        parent::$client->get('/api/tasks');
    }
    
    public function testGetOneTasks()
    {
        $bearerToken = parent::getBearerToken();
        $response = parent::$client->get(
            '/api/tasks/1',
            [
                'headers' => [
                    'Authorization' => sprintf('Bearer %s', $bearerToken),
                ],
            ]
        );
        $this->assertEquals(200, $response->getStatusCode());
        
        $responseContent = $response->getBody()->getContents();
        $responseJSONContent = json_decode($responseContent, true);
        
        $this->assertArrayHasKey('id', $responseJSONContent);
        $this->assertArrayHasKey('content', $responseJSONContent);
        $this->assertArrayHasKey('createdAt', $responseJSONContent);
        $this->assertArrayHasKey('isCompleted', $responseJSONContent);
    }
    
    public function testGetOneTasksWithoutAuthorization()
    {
        $this->setExpectedException(ClientException::class);
        
        parent::$client->get('/api/tasks/1');
    }
    
    public function testCreateTask()
    {
        $bearerToken = parent::getBearerToken();
        $response = parent::$client->post(
            '/api/tasks',
            [
                'headers'     => [
                    'Authorization' => sprintf('Bearer %s', $bearerToken),
                ],
                'form_params' => [
                    'task[content]' => 'new super task',
                ],
            ]
        );
        $this->assertEquals(201, $response->getStatusCode());
        
        $responseContent = $response->getBody()->getContents();
        $responseJSONContent = json_decode($responseContent, true);
        
        $this->assertArrayHasKey('id', $responseJSONContent);
        $this->assertArrayHasKey('content', $responseJSONContent);
        $this->assertArrayHasKey('createdAt', $responseJSONContent);
        $this->assertArrayHasKey('isCompleted', $responseJSONContent);
        $this->assertEquals('3', $responseJSONContent['id']);
        $this->assertEquals('new super task', $responseJSONContent['content']);
    }
    
    public function testPutTask()
    {
        $bearerToken = parent::getBearerToken();
        $response = parent::$client->put(
            '/api/tasks/1',
            [
                'headers'     => [
                    'Authorization' => sprintf('Bearer %s', $bearerToken),
                ],
                'form_params' => [
                    'task[content]' => 'edited task',
                ],
            ]
        );
        $this->assertEquals(200, $response->getStatusCode());
        
        $responseContent = $response->getBody()->getContents();
        $responseJSONContent = json_decode($responseContent, true);
        
        $this->assertArrayHasKey('id', $responseJSONContent);
        $this->assertArrayHasKey('content', $responseJSONContent);
        $this->assertArrayHasKey('createdAt', $responseJSONContent);
        $this->assertArrayHasKey('isCompleted', $responseJSONContent);
        $this->assertEquals('edited task', $responseJSONContent['content']);
    }
    
    public function testDeleteTask()
    {
        $bearerToken = parent::getBearerToken();
        $response = parent::$client->delete(
            '/api/tasks/1',
            [
                'headers' => [
                    'Authorization' => sprintf('Bearer %s', $bearerToken),
                ],
            ]
        );
        
        $this->assertEquals(200, $response->getStatusCode());
        
        $responseContent = $response->getBody()->getContents();
        $responseJSONContent = json_decode($responseContent, true);
    
        $this->assertArrayHasKey('deleted', $responseJSONContent);
        $this->assertEquals(true, $responseJSONContent['deleted']);
    }
    

    public function testDeleteTaskWithoutAuthorization()
    {
        $this->setExpectedException(ClientException::class);
    
        parent::$client->delete('/api/tasks/1');
    }
    
    public function testChangeStateTask()
    {
        $bearerToken = parent::getBearerToken();
        $response = parent::$client->post(
            '/api/tasks/2/change-state',
            [
                'headers' => [
                    'Authorization' => sprintf('Bearer %s', $bearerToken),
                ],
            ]
        );
    
        $this->assertEquals(200, $response->getStatusCode());
    
        $responseContent = $response->getBody()->getContents();
        $responseJSONContent = json_decode($responseContent, true);
    
        $this->assertArrayHasKey('id', $responseJSONContent);
        $this->assertArrayHasKey('content', $responseJSONContent);
        $this->assertArrayHasKey('createdAt', $responseJSONContent);
        $this->assertArrayHasKey('isCompleted', $responseJSONContent);
        $this->assertEquals(true, $responseJSONContent['isCompleted']);
    }
}