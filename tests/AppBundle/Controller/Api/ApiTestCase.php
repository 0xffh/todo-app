<?php

namespace Tests\AppBundle\Controller\Api;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;

abstract class ApiTestCase extends WebTestCase
{
    /**
     * @var Client
     */
    protected static $client;

    /**
     * @var Application
     */
    protected static $application;

    public static function setUpBeforeClass()
    {
        self::runCommand('doctrine:database:drop --force --if-exists');
        self::runCommand('doctrine:database:create');

        /*
         * doctrine:migrations:migrate --allow-no-migration почемуто падает с ошибкой
         * Migration version 20171214234321 already registered with class Doctrine\DBAL\Migrations\Version
         */
//        self::runCommand('doctrine:migrations:migrate --allow-no-migration');

        self::runCommand('doctrine:schema:update --force');

        static::$client = new Client([
            'base_uri' => static::$kernel->getContainer()->getParameter('server_host')
        ]);
    }

    protected function setUp()
    {
        self::runCommand('doctrine:fixtures:load --purge-with-truncate -n');
    }

    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new StringInput($command));
    }

    protected static function getApplication()
    {
        if (null === self::$application) {
            $client = static::createClient();

            self::$application = new Application($client->getKernel());
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }

    protected static function getBearerToken(): ?string
    {
        $response = self::$client->post(
            '/api/login_check',
            [
                'form_params' => [
                    '_username' => 'user-one',
                    '_password' => 'qwerty123',
                ],
            ]
        );
        $responseContent = $response->getBody()->getContents();
        $responseJSONContent = json_decode($responseContent, true);

        return $responseJSONContent['token'] ?? null;
    }

}