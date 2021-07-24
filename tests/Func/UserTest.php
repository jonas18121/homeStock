<?php

declare(strict_types=1);

namespace App\Tests\Func;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Entity\StorageSpace;
use App\Tests\Func\AbstractEndPoint;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; // tester les controlleurs et l'application en générale

/**
 * https://phpunit.readthedocs.io/fr/latest/textui.html
 * 
 * php bin/phpunit tests/Func/UserTest.php
 */
class UserTest extends AbstractEndPoint
{
    private string $userPayload = '{"email": "%s", "password": "password"}';

    /**
     * Accéder à la page d'accueil sans être connecter
     *
     * @return void
     */
    public function testGetHomeUserAnonymous() : void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_GET, '/');

        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * Accéder a un utilisateur en mode anonyme
     * 
     * code 302 found
     *
     * rediriger vers la page d'accueil
     * @return void
     */
    public function testGetOneUserAnonymous() : void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_GET, '/user/2');

        self::assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        self::assertResponseRedirects('/storageSpace');
    }

    /**
     * Accéder a un utilisateur en mode connecter
     * 
     * @return void
     */
    public function GetOneUserConnected() : void
    {
        $client = self::createClient();

        $response = $this->getResponseFromRequest(
            Request::METHOD_GET, 
            '/user/2',
            '',
            [],
            true
        );

        dd($response );

        self::assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        
    }

    /**
     * généré un email aléatoire
     *
     * @return string
     */
    private function getPayload(): string
    {
        $faker = Factory::create();

        return sprintf($this->userPayload, $faker->email);
    }
}