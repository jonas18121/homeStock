<?php

declare(strict_types=1);

namespace App\Tests\Func;

use App\DataFixtures\AppFixtures;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractEndPoint extends WebTestCase
{
    protected array $serverInformations = ['ACCEPT' => 'text/html', 'CONTENT_TYPE' => 'text/html'];
    protected string $notYourResource = 'It\'s not your resource';
    protected string $loginPayload = '{"username": "%s", "password": "%s"}';

    public function getResponseFromRequest(
        string $method,
        string $uri,
        string $payload = '',
        array $parameter = [],
        bool $withAuthentification = true
    ): Response {
        
        $client = $this->createAuthentificationClient($withAuthentification);

        $client->request(
            $method,
            $uri,
            $parameter,
            [],
            $this->serverInformations,
            $payload
        );

        return $client->getResponse();
    }

    protected function createAuthentificationClient(bool $withAuthentification)
    {
        $client = static::createClient();

        if (!$withAuthentification) {
            return $client;
        }

        $crawler = $client->request(
            Request::METHOD_GET,
            '/login',
        );

        $form = $crawler->selectButton('Se connecter')->form([
            'email' => AppFixtures::DEFAULT_USER['email'],
            'password' => AppFixtures::DEFAULT_USER['password']
        ]);

        $client->submit($form);

        return $client;
    }
}
