<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase; // on va utiliser le kernel

/**
 * https://phpunit.readthedocs.io/fr/latest/textui.html
 * 
 * php bin/phpunit tests/Repository/UserRepositoryTest.php
 */
class UserRepositoryTest extends KernelTestCase 
{
    /**
     * compter le nombre d'utilisateur dans la bdd
     *
     * @return void
     */
    public function testCount()
    {
        self::bootKernel(); //démarre le kernel

        /** @var UserRepository|null */
        $userRepository = self::$container->get(UserRepository::class);

        if (null !== $userRepository) {
            $nb_user = $userRepository->countUser();// on recupère le repository et exécute count()
            $this->assertEquals(2, $nb_user);
        }
    }
}