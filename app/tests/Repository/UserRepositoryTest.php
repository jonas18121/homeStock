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

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase; // on va utiliser le kernel

/**
 * https://phpunit.readthedocs.io/fr/latest/textui.html.
 *
 * php bin/phpunit tests/Repository/UserRepositoryTest.php
 */
class UserRepositoryTest extends KernelTestCase
{
    /**
     * compter le nombre d'utilisateur dans la bdd.
     *
     * @return void
     */
    public function testCount()
    {
        self::bootKernel(); // démarre le kernel

        /** @var UserRepository|null */
        $userRepository = self::$container->get(UserRepository::class);

        if (null !== $userRepository) {
            $nb_user = $userRepository->countUser(); // on recupère le repository et exécute count()
            $this->assertSame(2, $nb_user);
        }
    }
}
