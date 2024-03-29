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

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\TraceableValidator;

/**
 * https://phpunit.readthedocs.io/fr/latest/textui.html.
 *
 * php bin/phpunit tests/Entity/UserValidationTest.php
 */
class UserValidationTest extends KernelTestCase
{
    /**
     * @see \Doctrine\DBAL\Logging\Middleware
     *
     * On test si les données qu'on rentre par exemple dans un formulaire sont valides ou pas.
     *
     * ici on attend zéro erreur
     *
     * @return void
     */
    public function testValidEntity()
    {
        self::bootKernel();

        $user = new User();

        $user->setEmail('test@gmail.com')
            ->setPassword('Test1-Gma')
            ->setConfirmPassword('Test1-Gma')
            ->setFirstName('FirstName')
            ->setLastName('LastName')
        ;

        /** @var TraceableValidator */
        $validator = self::$container->get('validator');
        $error = $validator->validate($user);

        $this->assertCount(0, $error);
    }

    /**
     * On test si les données qu'on rentre par exemple dans un formulaire sont valides ou pas.
     *
     * ici on attend une erreur, car le mail n'est pas correcte
     * il manque le caractère spéciale arobase @
     *
     * @return void
     */
    public function testInvalidEmailEntity()
    {
        $user = new User();

        $user->setEmail('testgmail.com')
            ->setPassword('Test1-Gma')
            ->setConfirmPassword('Test1-Gma')
            ->setFirstName('FirstName')
            ->setLastName('LastName')
        ;

        self::bootKernel();

        /** @var TraceableValidator */
        $validator = self::$container->get('validator');
        $error = $validator->validate($user);

        $this->assertCount(1, $error);
    }

    /**
     * On test si les données qu'on rentre par exemple dans un formulaire sont valides ou pas.
     *
     * ici on attend une erreur, car le password n'est pas correcte
     * il manque au moin un chiffre
     *
     * @return void
     */
    public function testInvalidPasswordEntity()
    {
        $user = new User();

        $user->setEmail('test@gmail.com')
            ->setPassword('Test-Gma')
            ->setConfirmPassword('Test-Gma')
            ->setFirstName('FirstName')
            ->setLastName('LastName')
        ;

        self::bootKernel();

        /** @var TraceableValidator */
        $validator = self::$container->get('validator');
        $error = $validator->validate($user);

        $this->assertCount(1, $error);
    }

    /**
     * On test si les données qu'on rentre par exemple dans un formulaire sont valides ou pas.
     *
     * ici on attend une erreur, car le confirm_password n'est pas correcte
     * il n'est pas égale au champ password
     *
     * @return void
     */
    public function testInvalidConfirmPasswordEntity()
    {
        $user = new User();

        $user->setEmail('test@gmail.com')
            ->setPassword('Test1-Gma')
            ->setConfirmPassword('Test')
            ->setFirstName('FirstName')
            ->setLastName('LastName')
        ;

        self::bootKernel();

        /** @var TraceableValidator */
        $validator = self::$container->get('validator');
        $error = $validator->validate($user);

        $this->assertCount(1, $error);
    }

    /**
     * On test si les données qu'on rentre par exemple dans un formulaire sont valides ou pas.
     *
     * ici on attend une erreur, car le champ firstName n'est pas correcte
     * il est a moins de deux caractères
     *
     * @return void
     */
    public function testInvalidFirstNameEntity()
    {
        $user = new User();

        $user->setEmail('test@gmail.com')
            ->setPassword('Test1-Gma')
            ->setConfirmPassword('Test1-Gma')
            ->setFirstName('F')
            ->setLastName('LastName')
        ;

        self::bootKernel();

        /** @var TraceableValidator */
        $validator = self::$container->get('validator');
        $error = $validator->validate($user);

        $this->assertCount(1, $error);
    }

    /**
     * On test si les données qu'on rentre par exemple dans un formulaire sont valides ou pas.
     *
     * ici on attend une erreur, car le champ LastName n'est pas correcte
     * il est a moins de deux caractères
     *
     * @return void
     */
    public function testInvalidLastNameEntity()
    {
        $user = new User();

        $user->setEmail('test@gmail.com')
            ->setPassword('Test1-Gma')
            ->setConfirmPassword('Test1-Gma')
            ->setFirstName('FirstName')
            ->setLastName('L')
        ;

        self::bootKernel();

        /** @var TraceableValidator */
        $validator = self::$container->get('validator');
        $error = $validator->validate($user);

        $this->assertCount(1, $error);
    }
}
