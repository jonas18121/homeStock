<?php

declare(strict_types=1);

namespace App\Tests\Func;

use App\Entity\User;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Entity\StorageSpace;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; // tester les controlleurs et l'application en générale

/**
 * https://phpunit.readthedocs.io/fr/latest/textui.html
 * 
 * php bin/phpunit tests/Func/UserTest.php
 */
class UserTest extends WebTestCase
{
    /**
     * faire la connection user
     *
     * @return void
     */
    public function testGetOneUser() : void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_GET, '/');

        // dd($client->getResponse());

        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}