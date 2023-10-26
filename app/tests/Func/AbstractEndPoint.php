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
    /** @var array<string, string> */
    protected $serverInformations = ['ACCEPT' => 'text/html', 'CONTENT_TYPE' => 'text/html'];
    protected string $notYourResource = 'It\'s not your resource';
    protected string $loginPayload = '{"username": "%s", "password": "%s"}';

    /**
     * Undocumented function
     *
     * @param string $method
     * @param string $uri
     * @param string $payload
     * @param array<string, string> $parameter
     * @param boolean $withAuthentification
     * @return Response
     */
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

    protected function createAuthentificationClient(bool $withAuthentification): KernelBrowser
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
