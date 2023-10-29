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

use App\Entity\Category;
use App\Entity\StorageSpace;
use PHPUnit\Framework\TestCase;

/**
 * https://phpunit.readthedocs.io/fr/latest/textui.html.
 *
 * php bin/phpunit tests/Unit/CategoryTest.php
 */
class CategoryTest extends TestCase
{
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = new Category();
    }

    public function testGetName(): void
    {
        $value = 'Box';

        $response = $this->category->setName($value);

        self::assertInstanceOf(Category::class, $response);
        self::assertSame($value, $this->category->getName());
    }

    /**
     * Ajouter un espace de stockage à une categorie
     * Afficher un espace de stockage à une categorie
     * Supprimer un espace de stockage à une categorie.
     */
    public function testStorageSpace(): void
    {
        $value = new StorageSpace();

        $response = $this->category->addStorageSpace($value);

        self::assertInstanceOf(Category::class, $response);
        self::assertCount(1, $this->category->getStorageSpaces());

        // est ce qu'il contient notre value
        self::assertTrue($this->category->getStorageSpaces()->contains($value));

        $response = $this->category->removeStorageSpace($value);

        self::assertInstanceOf(Category::class, $response);
        self::assertCount(0, $this->category->getStorageSpaces());
        self::assertFalse($this->category->getStorageSpaces()->contains($value));
    }

    /**
     * Ajouter plusieurs espaces de stockage à une categorie
     * Afficher plusieurs espaces de stockage à une categorie
     * Supprimer plusieurs espaces de stockage à une categorie.
     */
    public function testStorageSpaces(): void
    {
        $value = new StorageSpace();
        $value1 = new StorageSpace();
        $value2 = new StorageSpace();

        $response = $this->category->addStorageSpace($value);
        $response1 = $this->category->addStorageSpace($value1);
        $response2 = $this->category->addStorageSpace($value2);

        self::assertInstanceOf(Category::class, $response);
        self::assertInstanceOf(Category::class, $response1);
        self::assertInstanceOf(Category::class, $response2);
        self::assertCount(3, $this->category->getStorageSpaces());
        self::assertTrue($this->category->getStorageSpaces()->contains($value));
        self::assertTrue($this->category->getStorageSpaces()->contains($value1));
        self::assertTrue($this->category->getStorageSpaces()->contains($value2));

        $response = $this->category->removeStorageSpace($value);

        self::assertInstanceOf(Category::class, $response);
        self::assertCount(2, $this->category->getStorageSpaces());
        self::assertFalse($this->category->getStorageSpaces()->contains($value));
        self::assertTrue($this->category->getStorageSpaces()->contains($value1));
        self::assertTrue($this->category->getStorageSpaces()->contains($value2));
    }
}
