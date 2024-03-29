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
use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * https://phpunit.readthedocs.io/fr/latest/textui.html.
 *
 * php bin/phpunit tests/Unit/BookingTest.php
 */
class BookingTest extends TestCase
{
    private Booking $booking;

    protected function setUp(): void
    {
        parent::setUp();

        $this->booking = new Booking();
    }

    public function testGetCreatedAt(): void
    {
        $value = new \DateTime('now');

        $response = $this->booking->setCreatedAt($value);

        self::assertInstanceOf(Booking::class, $response);
        self::assertSame($value, $this->booking->getCreatedAt());
    }

    public function testGetDateStartAt(): void
    {
        $value = new \DateTime('now');

        $response = $this->booking->setDateStartAt($value);

        self::assertInstanceOf(Booking::class, $response);
        self::assertSame($value, $this->booking->getDateStartAt());
    }

    public function testGetEndAt(): void
    {
        $value = new \DateTime('now');

        $response = $this->booking->setDateEndAt($value);

        self::assertInstanceOf(Booking::class, $response);
        self::assertSame($value, $this->booking->getDateEndAt());
    }

    public function testGetFinish(): void
    {
        $value = false;

        $response = $this->booking->setFinish($value);

        self::assertInstanceOf(Booking::class, $response);
        self::assertSame($value, $this->booking->getFinish());
        self::assertFalse($this->booking->getFinish());
    }

    public function testGetPay(): void
    {
        $value = false;

        $response = $this->booking->setPay($value);

        self::assertInstanceOf(Booking::class, $response);
        self::assertSame($value, $this->booking->getPay());
        self::assertFalse($this->booking->getPay());
    }

    public function testGetCheckForPayement(): void
    {
        $value = false;

        $response = $this->booking->setCheckForPayement($value);

        self::assertInstanceOf(Booking::class, $response);
        self::assertSame($value, $this->booking->getCheckForPayement());
        self::assertFalse($this->booking->getCheckForPayement());
    }

    /**
     * Ajouter pour allier un user à une réservation
     * Afficher le propriétaire (user) de la réservation.
     */
    public function testUser(): void
    {
        $value = new User();

        $response = $this->booking->setLodger($value);

        self::assertInstanceOf(Booking::class, $response);
        self::assertSame($value, $this->booking->getLodger());
    }
}
