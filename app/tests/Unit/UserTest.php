<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\User;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Entity\StorageSpace;
use PHPUnit\Framework\TestCase; 

/**
 * https://phpunit.readthedocs.io/fr/latest/textui.html
 * 
 * php bin/phpunit tests/Unit/UserTest.php
 */
class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
    }

    public function testGetEmail() : void
    {
        $value = 'test@test.fr';

        $response = $this->user->setEmail($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getEmail());
        self::assertEquals($value, $this->user->getUsername());
    }

    /**
     * assertContains(), pour les tableaux
     * 
     * 'ROLE_USER' fait partie de 'ROLE_ADMIN'
     *
     * @return void
     */
    public function testGetRoles() : void
    {
        $value = ['ROLE_ADMIN'];

        $response = $this->user->setRoles($value);

        self::assertInstanceOf(User::class, $response);
        self::assertContains('ROLE_USER', $this->user->getRoles());
        self::assertContains('ROLE_ADMIN', $this->user->getRoles());
    }

    public function testGetPassword() : void
    {
        $value = 'password';

        $response = $this->user->setPassword($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getPassword());
    }

    public function testGetLastName() : void
    {
        $value = 'Doe';

        $response = $this->user->setLastName($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getLastName());
    }

    public function testGetFirstName() : void
    {
        $value = 'Jhon';

        $response = $this->user->setFirstName($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getFirstName());
    }

    public function testGetImages() : void
    {
        $value = 'myImage.jpg';

        $response = $this->user->setImages($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getImages());
    }

    public function testGetCreatedAt() : void
    {
        $value = new \DateTime('now');

        $response = $this->user->setCreatedAt($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getCreatedAt());
    }

    public function testGetUpdateAt() : void
    {
        $value = new \DateTime('now');

        $response = $this->user->setUpdatedAt($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getUpdatedAt());
    }

    public function testGetPhoneNumber() : void
    {
        $value = '0644112233';

        $response = $this->user->setPhoneNumber($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getPhoneNumber());
    }

    /**
     * Ajouter un espace de stockage
     * Afficher un espace de stockage
     * Supprimer un espace de stockage
     *
     * @return void
     */
    public function testStorageSpace(): void
    {
        $value = new StorageSpace();

        $response = $this->user->addStorageSpace($value);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(1, $this->user->getStorageSpaces());

        // est ce qu'il contient notre value
        self::assertTrue($this->user->getStorageSpaces()->contains($value));

        
        $response = $this->user->removeStorageSpace($value);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(0, $this->user->getStorageSpaces());
        self::assertFalse($this->user->getStorageSpaces()->contains($value));
    }

    /**
     * Ajouter plusieurs espaces de stockage
     * Afficher plusieurs espaces de stockage
     * Supprimer plusieurs espaces de stockage
     *
     * @return void
     */
    public function testStorageSpaces(): void
    {
        $value = new StorageSpace();
        $value1 = new StorageSpace();
        $value2 = new StorageSpace();

        $this->user->addStorageSpace($value);
        $this->user->addStorageSpace($value1);
        $this->user->addStorageSpace($value2);

        self::assertCount(3, $this->user->getStorageSpaces());
        self::assertTrue($this->user->getStorageSpaces()->contains($value));
        self::assertTrue($this->user->getStorageSpaces()->contains($value1));
        self::assertTrue($this->user->getStorageSpaces()->contains($value2));


        $response = $this->user->removeStorageSpace($value);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(2, $this->user->getStorageSpaces());
        self::assertFalse($this->user->getStorageSpaces()->contains($value));
        self::assertTrue($this->user->getStorageSpaces()->contains($value1));
        self::assertTrue($this->user->getStorageSpaces()->contains($value2));
    }

    /**
     * Ajouter un Commentaire
     * Afficher un Commentaire
     * Supprimer un Commentaire
     *
     * @return void
     */
    public function testComment(): void
    {
        $value = new Comment();

        $response = $this->user->addComment($value);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(1, $this->user->getComments());

        // est ce qu'il contient notre value
        self::assertTrue($this->user->getComments()->contains($value));

        
        $response = $this->user->removeComment($value);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(0, $this->user->getComments());
        self::assertFalse($this->user->getComments()->contains($value));
    }

    /**
     * Ajouter plusieurs Commentaires
     * Afficher plusieurs Commentaires
     * supprimer plusieurs Commentaires
     *
     * @return void
     */
    public function testComments(): void
    {
        $value = new Comment();
        $value1 = new Comment();
        $value2 = new Comment();

        $this->user->addComment($value);
        $this->user->addComment($value1);
        $this->user->addComment($value2);

        self::assertCount(3, $this->user->getComments());
        self::assertTrue($this->user->getComments()->contains($value));
        self::assertTrue($this->user->getComments()->contains($value1));
        self::assertTrue($this->user->getComments()->contains($value2));


        $response = $this->user->removeComment($value);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(2, $this->user->getComments());
        self::assertFalse($this->user->getComments()->contains($value));
        self::assertTrue($this->user->getComments()->contains($value1));
        self::assertTrue($this->user->getComments()->contains($value2));
    }

    /**
     * Ajouter une réservation
     * Afficher une réservation
     * supprimer une réservation
     *
     * @return void
     */
    public function testBooking(): void
    {
        $value = new Booking();

        $response = $this->user->addBooking($value);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(1, $this->user->getBookings());

        // est ce qu'il contient notre value
        self::assertTrue($this->user->getBookings()->contains($value));

        
        $response = $this->user->removeBooking($value);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(0, $this->user->getBookings());
        self::assertFalse($this->user->getBookings()->contains($value));
    }

    /**
     * Ajouter plusieurs Réservations
     * Afficher plusieurs Réservations
     * supprimer plusieurs Réservations
     *
     * @return void
     */
    public function testBookings(): void
    {
        $value = new Booking();
        $value1 = new Booking();
        $value2 = new Booking();

        $this->user->addBooking($value);
        $this->user->addBooking($value1);
        $this->user->addBooking($value2);

        self::assertCount(3, $this->user->getBookings());
        self::assertTrue($this->user->getBookings()->contains($value));
        self::assertTrue($this->user->getBookings()->contains($value1));
        self::assertTrue($this->user->getBookings()->contains($value2));


        $response = $this->user->removeBooking($value);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(2, $this->user->getBookings());
        self::assertFalse($this->user->getBookings()->contains($value));
        self::assertTrue($this->user->getBookings()->contains($value1));
        self::assertTrue($this->user->getBookings()->contains($value2));
    }
}