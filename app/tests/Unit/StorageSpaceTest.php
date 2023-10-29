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

namespace App\Tests\Unit;

use App\Entity\Booking;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\StorageSpace;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * https://phpunit.readthedocs.io/fr/latest/textui.html.
 *
 * php bin/phpunit tests/Unit/StorageSpaceTest.php
 */
class StorageSpaceTest extends TestCase
{
    private StorageSpace $storageSpace;

    protected function setUp(): void
    {
        parent::setUp();

        $this->storageSpace = new StorageSpace();
    }

    public function testGetTitle(): void
    {
        $value = 'Super garage à loué';

        $response = $this->storageSpace->setTitle($value);

        self::assertInstanceOf(StorageSpace::class, $response);
        self::assertSame($value, $this->storageSpace->getTitle());
    }

    public function testGetDescription(): void
    {
        $value = 'Super garage à loué, très spacieux de 20 m2, bien éclairé, bien isolé';

        $response = $this->storageSpace->setDescription($value);

        self::assertInstanceOf(StorageSpace::class, $response);
        self::assertSame($value, $this->storageSpace->getDescription());
    }

    public function testGetAdresse(): void
    {
        $value = '9 rue des madières';

        $response = $this->storageSpace->setAdresse($value);

        self::assertInstanceOf(StorageSpace::class, $response);
        self::assertSame($value, $this->storageSpace->getAdresse());
    }

    public function testGetCity(): void
    {
        $value = 'Langueux';

        $response = $this->storageSpace->setCity($value);

        self::assertInstanceOf(StorageSpace::class, $response);
        self::assertSame($value, $this->storageSpace->getCity());
    }

    public function testGetPostalCode(): void
    {
        $value = '22390';

        $response = $this->storageSpace->setPostalCode($value);

        self::assertInstanceOf(StorageSpace::class, $response);
        self::assertSame($value, $this->storageSpace->getPostalCode());
    }

    public function testGetSpace(): void
    {
        $value = 45;

        $response = $this->storageSpace->setSpace($value);

        self::assertInstanceOf(StorageSpace::class, $response);
        self::assertSame($value, $this->storageSpace->getSpace());
    }

    public function testGetPriceByDays(): void
    {
        $value = 1.33;

        $response = $this->storageSpace->setPriceByDays($value);

        self::assertInstanceOf(StorageSpace::class, $response);
        self::assertSame($value, $this->storageSpace->getPriceByDays());
    }

    public function testGetPriceByMonth(): void
    {
        $value = 39.9;

        $response = $this->storageSpace->setPriceByMonth($value);

        self::assertInstanceOf(StorageSpace::class, $response);
        self::assertSame($value, $this->storageSpace->getPriceByMonth());
    }

    public function testGetImages(): void
    {
        $value = 'myImage.jpg';

        $response = $this->storageSpace->setImages($value);

        self::assertInstanceOf(StorageSpace::class, $response);
        self::assertSame($value, $this->storageSpace->getImages());
    }

    public function testGetCreatedAt(): void
    {
        $value = new \DateTime('now');

        $response = $this->storageSpace->setCreatedAt($value);

        self::assertInstanceOf(StorageSpace::class, $response);
        self::assertSame($value, $this->storageSpace->getCreatedAt());
    }

    public function testGetUpdatedAt(): void
    {
        $value = new \DateTime('now');

        $response = $this->storageSpace->setUpdatedAt($value);

        self::assertInstanceOf(StorageSpace::class, $response);
        self::assertSame($value, $this->storageSpace->getUpdatedAt());
    }

    /**
     * Ajouter un Commentaire dans un espace de stockage
     * Afficher un Commentaire dans un espace de stockage
     * Supprimer un Commentaire dans un espace de stockage.
     */
    public function testComment(): void
    {
        $value = new Comment();

        $response = $this->storageSpace->addComment($value);

        self::assertInstanceOf(StorageSpace::class, $response);
        self::assertCount(1, $this->storageSpace->getComments());

        // est ce qu'il contient notre value
        self::assertTrue($this->storageSpace->getComments()->contains($value));

        $response = $this->storageSpace->removeComment($value);

        self::assertInstanceOf(StorageSpace::class, $response);
        self::assertCount(0, $this->storageSpace->getComments());
        self::assertFalse($this->storageSpace->getComments()->contains($value));
    }

    /**
     * Ajouter plusieurs Commentaires dans un espace de stockage
     * Afficher plusieurs Commentaires dans un espace de stockage
     * supprimer plusieurs Commentaires dans un espace de stockage.
     */
    public function testComments(): void
    {
        $value = new Comment();
        $value1 = new Comment();
        $value2 = new Comment();

        $this->storageSpace->addComment($value);
        $this->storageSpace->addComment($value1);
        $this->storageSpace->addComment($value2);

        self::assertCount(3, $this->storageSpace->getComments());
        self::assertTrue($this->storageSpace->getComments()->contains($value));
        self::assertTrue($this->storageSpace->getComments()->contains($value1));
        self::assertTrue($this->storageSpace->getComments()->contains($value2));

        $response = $this->storageSpace->removeComment($value);

        self::assertInstanceOf(storageSpace::class, $response);
        self::assertCount(2, $this->storageSpace->getComments());
        self::assertFalse($this->storageSpace->getComments()->contains($value));
        self::assertTrue($this->storageSpace->getComments()->contains($value1));
        self::assertTrue($this->storageSpace->getComments()->contains($value2));
    }

    /**
     * Ajouter une reservation à un espace de stockage
     * Afficher une reservation à un espace de stockage
     * Supprimer une reservation à un espace de stockage.
     */
    public function testBooking(): void
    {
        $value = new Booking();

        $response = $this->storageSpace->addBooking($value);

        self::assertInstanceOf(StorageSpace::class, $response);
        self::assertCount(1, $this->storageSpace->getBookings());

        // est ce qu'il contient notre value
        self::assertTrue($this->storageSpace->getBookings()->contains($value));

        $response = $this->storageSpace->removeBooking($value);

        self::assertInstanceOf(StorageSpace::class, $response);
        self::assertCount(0, $this->storageSpace->getBookings());
        self::assertFalse($this->storageSpace->getBookings()->contains($value));
    }

    /**
     * Ajouter plusieurs réservation à un espace de stockage
     * Afficher plusieurs réservation à un espace de stockage
     * supprimer plusieurs réservation à un espace de stockage.
     */
    public function testBookings(): void
    {
        $value = new Booking();
        $value1 = new Booking();
        $value2 = new Booking();

        $this->storageSpace->addBooking($value);
        $this->storageSpace->addBooking($value1);
        $this->storageSpace->addBooking($value2);

        self::assertCount(3, $this->storageSpace->getBookings());
        self::assertTrue($this->storageSpace->getBookings()->contains($value));
        self::assertTrue($this->storageSpace->getBookings()->contains($value1));
        self::assertTrue($this->storageSpace->getBookings()->contains($value2));

        $response = $this->storageSpace->removeBooking($value);

        self::assertInstanceOf(storageSpace::class, $response);
        self::assertCount(2, $this->storageSpace->getBookings());
        self::assertFalse($this->storageSpace->getBookings()->contains($value));
        self::assertTrue($this->storageSpace->getBookings()->contains($value1));
        self::assertTrue($this->storageSpace->getBookings()->contains($value2));
    }

    /**
     * Ajouter pour allier espace de stockage a une catégorie
     * Afficher la catégorie de l'espace de stockage.
     */
    public function testCategory(): void
    {
        $value = new Category();

        $response = $this->storageSpace->setCategory($value);

        self::assertInstanceOf(StorageSpace::class, $response);
        self::assertSame($value, $this->storageSpace->getCategory());
    }

    /**
     * Ajouter pour allier un user a un espace de stockager
     * Afficher le propriétaire (user) de l'espace de stockage.
     */
    public function testUser(): void
    {
        $value = new User();

        $response = $this->storageSpace->setOwner($value);

        self::assertInstanceOf(StorageSpace::class, $response);
        self::assertSame($value, $this->storageSpace->getOwner());
    }
}
