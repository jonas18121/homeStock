<?php

declare(strict_types=1);

namespace App\Tests\Func;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Entity\StorageSpace;
use App\DataFixtures\AppFixtures;
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
    private  $client;


    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
    }

    /**
     * Accéder à la page d'accueil sans être connecter
     *
     * @return void
     */
    public function testGetHomeUserAnonymous() : void
    {
        // $client = self::createClient();

        $this->client->request(Request::METHOD_GET, '/');

        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
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
        // $client = self::createClient();

        $this->client->request(Request::METHOD_GET, '/user/2');

        self::assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        self::assertResponseRedirects('/storageSpace');
    }

    /**
     * On test la page login lorsqu'un utilisateur se connecte avec des fausses données
     * Il doit être rediriger vers la page /login
     * On suit la redirection
     * On verifie que la classe .error_form_login est bien présente afin d'afficher une erreur à l'utilisateur
     * 
     * @return void
     */
    public function testUserLoginBad() : void
    {
        $crawler = $this->client->request(
            Request::METHOD_GET,
            '/login',
        );

        $form = $crawler->selectButton('Se connecter')->form([
            'email' => AppFixtures::DEFAULT_USER['email'],
            'password' => 'fakePassword'
        ]);

        $this->client->submit($form);

        self::assertResponseRedirects('/login');
        $this->client->followRedirect();
        self::assertSelectorExists('.error_form_login');
    }

    /**
     * On test la page login lorsqu'un utilisateur se connecte avec des données valide
     * Il doit être rediriger vers la page /storageSpace
     * 
     * @return void
     */
    public function testUserLoginSuccess() : void
    {
        $crawler = $this->client->request(
            Request::METHOD_GET,
            '/login',
        );

        $form = $crawler->selectButton('Se connecter')->form([
            'email' => AppFixtures::DEFAULT_USER['email'],
            'password' => AppFixtures::DEFAULT_USER['password']
        ]);

        $this->client->submit($form);

        self::assertResponseRedirects('/storageSpace');
        $this->client->followRedirect();
    }

    /**
     * On test le contrôleur SecurityController::login
     * lorsqu'un utilisateur se connecte avec des données valide
     * 
     * On récupère le token CSRF du formulaire et on le passe en paramètre avec le email et le password
     * on doit être rediriger vers la page /storageSpace
     * 
     * 
     * @return void
     */
    public function testUserControllerLoginSuccess() : void
    {
        $csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('authenticate');

        $this->client->request(
            Request::METHOD_POST,
            '/login',
            [
                'email'         => AppFixtures::DEFAULT_USER['email'],
                'password'      => AppFixtures::DEFAULT_USER['password'],
                '_csrf_token'   => $csrfToken
            ]
        );

        self::assertResponseRedirects('/storageSpace');
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