<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Func;

use App\DataFixtures\AppFixtures;
use App\Entity\StorageSpace;
use App\Entity\User;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

// tester les controlleurs et l'application en générale

/**
 * https://phpunit.readthedocs.io/fr/latest/textui.html.
 *
 * php bin/phpunit tests/Func/UserTest.php
 */
class UserTest extends AbstractEndPoint
{
    private KernelBrowser $client;
    private string $userPayload = '{"email": "%s", "password": "password"}';

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
    }

    /**
     * Accéder à la page d'accueil sans être connecter.
     */
    public function testGetHomeUserAnonymous(): void
    {
        $this->client->request(Request::METHOD_GET, '/');

        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Accéder a un utilisateur en mode anonyme.
     *
     * code 302 found
     *
     * rediriger vers la page d'accueil
     */
    public function testGetOneUserAnonymous(): void
    {
        $this->client->request(Request::METHOD_GET, '/user/2');

        self::assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        self::assertResponseRedirects('/storageSpace');
    }

    /**
     * On test la page login lorsqu'un utilisateur se connecte avec des fausses données
     * Il doit être rediriger vers la page /login
     * On suit la redirection
     * On verifie que la classe .error_form_login est bien présente afin d'afficher une erreur à l'utilisateur.
     */
    public function testUserLoginBad(): void
    {
        $crawler = $this->client->request(
            Request::METHOD_GET,
            '/login',
        );

        $form = $crawler->selectButton('Se connecter')->form([
            'email' => AppFixtures::DEFAULT_USER['email'],
            'password' => 'fakePassword',
        ]);

        $this->client->submit($form);

        self::assertResponseRedirects('/login');
        $this->client->followRedirect();
        self::assertSelectorExists('.error_form_login');
    }

    /**
     * On test la page login lorsqu'un utilisateur se connecte avec des données valide
     * Il doit être rediriger vers la page /storageSpace.
     */
    public function testUserLoginSuccess(): void
    {
        $crawler = $this->client->request(
            Request::METHOD_GET,
            '/login',
        );

        $form = $crawler->selectButton('Se connecter')->form([
            'email' => AppFixtures::DEFAULT_USER['email'],
            'password' => AppFixtures::DEFAULT_USER['password'],
        ]);

        $this->client->submit($form);

        // self::assertResponseRedirects('/login');
        self::assertResponseRedirects('/storageSpace');
    }

    /**
     * On test le contrôleur SecurityController::login
     * lorsqu'un utilisateur se connecte avec des données valide.
     *
     * On récupère le token CSRF du formulaire et on le passe en paramètre avec le email et le password
     * on doit être rediriger vers la page /storageSpace
     */
    public function testSecurityControllerLoginSuccess(): void
    {
        /** @var ContainerInterface */
        $containerInterface = $this->client->getContainer();

        /** @var CsrfTokenManager */
        $tokenManager = $containerInterface->get('security.csrf.token_manager');

        $csrfToken = $tokenManager->getToken('authenticate');

        $this->client->request(
            Request::METHOD_POST,
            '/login',
            [
                'email' => AppFixtures::DEFAULT_USER['email'],
                'password' => AppFixtures::DEFAULT_USER['password'],
                '_csrf_token' => $csrfToken,
            ]
        );

        self::assertResponseRedirects('/storageSpace');
    }

    /**
     * L'utilisateur connecter accède à son profil
     * On vérifie si le status code est 200 pour la page /user/{id}.
     */
    public function testGetOneUserWithUserConnected(): void
    {
        $user = $this->createUser();

        $this->userLogin($this->client, $user);

        $this->client->request(
            Request::METHOD_GET,
            "/user/{$user->getId()}"
        );

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * L'utilisateur en role admin accède à la page /amdin
     * On vérifie si le status code est 200 pour la page /amdin.
     */
    public function testGetAdminDasboardWithAdmin(): void
    {
        $user = $this->createUserAdmin();

        $this->userLogin($this->client, $user);

        $this->client->request(
            Request::METHOD_GET,
            '/admin'
        );

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * L'utilisateur non admin accède à la page /amdin
     * On vérifie si le status code est 403 pour la page /amdin.
     */
    public function testGetAdminDasboardWithUserNotAdmin(): void
    {
        $user = $this->createUser();

        $this->userLogin($this->client, $user);

        $this->client->request(
            Request::METHOD_GET,
            '/admin'
        );

        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * Créer un utilisateur admin.
     */
    private function createUserAdmin(): User
    {
        $user = new User();
        $user->setId(AppFixtures::DEFAULT_ADMIN['id'])
            ->setEmail(AppFixtures::DEFAULT_ADMIN['email'])
            ->setPassword(AppFixtures::DEFAULT_ADMIN['password_hash'])
            ->setLastName(AppFixtures::DEFAULT_ADMIN['lastName'])
            ->setFirstName(AppFixtures::DEFAULT_ADMIN['firstName'])
            ->setPhoneNumber(AppFixtures::DEFAULT_ADMIN['phoneNumber'])
            // ->setCreatedAt(AppFixtures::DEFAULT_USER['createdAt'])
            ->setRoles(AppFixtures::DEFAULT_ADMIN['roles_admin'])
        ;

        return $user;
    }

    /**
     * Créer un utilisateur.
     */
    private function createUser(): User
    {
        $user = new User();
        $user->setId(AppFixtures::DEFAULT_USER['id'])
            ->setEmail(AppFixtures::DEFAULT_USER['email'])
            ->setPassword(AppFixtures::DEFAULT_USER['password_hash'])
            ->setLastName(AppFixtures::DEFAULT_USER['lastName'])
            ->setFirstName(AppFixtures::DEFAULT_USER['firstName'])
            ->setPhoneNumber(AppFixtures::DEFAULT_USER['phoneNumber'])
            // ->setCreatedAt(AppFixtures::DEFAULT_USER['createdAt'])
            ->setRoles(AppFixtures::DEFAULT_USER['roles_user'])
        ;

        return $user;
    }

    /**
     * L' utilisateur se conntecte.
     *
     * On crée une session pour un utilisateur
     * puis on fait un cookie qui est lier à la session
     */
    private function userLogin(KernelBrowser $client, User $user): void
    {
        // Creer une session pour un utilisateur
        /** @var ContainerInterface */
        $containerInterface = $this->client->getContainer();

        /** @var SessionInterface */
        $session = $containerInterface->get('session'); // acceder au servvice de session

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles()); // généré le token
        $session->set('_security_main', serialize($token)); // firewall main
        $session->save();

        // faire un cookie qui est lier à la session
        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }

    /**
     * @phpstan-ignore-next-line
     *
     * généré un email aléatoire
     */
    private function getPayload(): string
    {
        $faker = Factory::create();

        return sprintf($this->userPayload, $faker->email);
    }
}
