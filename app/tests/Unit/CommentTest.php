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

use App\Entity\Comment;
use App\Entity\StorageSpace;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * https://phpunit.readthedocs.io/fr/latest/textui.html.
 *
 * php bin/phpunit tests/Unit/CommentTest.php
 */
class CommentTest extends TestCase
{
    private Comment $comment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->comment = new Comment();
    }

    public function testGetComment(): void
    {
        $value = 'Super ce garage est très bien entretenu';

        $response = $this->comment->setContent($value);

        self::assertInstanceOf(Comment::class, $response);
        self::assertSame($value, $this->comment->getContent());
    }

    public function testGetCreatedAt(): void
    {
        $value = new \DateTime('now');

        $response = $this->comment->setCreatedAt($value);

        self::assertInstanceOf(Comment::class, $response);
        self::assertSame($value, $this->comment->getCreatedAt());
    }

    /**
     * Ajouter un espace de stockage à un commentaire.
     */
    public function testStorageSpace(): void
    {
        $value = new StorageSpace();

        $response = $this->comment->setStorageSpace($value);

        self::assertInstanceOf(Comment::class, $response);
        self::assertSame($value, $this->comment->getStorageSpace());
    }

    /**
     * Ajouter un commentaire parent dans un espaces de stockage.
     */
    public function testParent(): void
    {
        $value = new Comment();

        $response = $this->comment->setParent($value);

        self::assertInstanceOf(Comment::class, $response);
        self::assertSame($value, $this->comment->getParent());
    }

    /**
     * Ajouter un Commentaire enfant dans un commentaire parent
     * Afficher un Commentaire enfant dans un commentaire parent
     * Supprimer un Commentaire enfant dans un commentaire parent.
     */
    public function testReply(): void
    {
        $value = new Comment();

        $response = $this->comment->addReply($value);

        self::assertInstanceOf(Comment::class, $response);
        self::assertCount(1, $this->comment->getReplies());

        // est ce qu'il contient notre value
        self::assertTrue($this->comment->getReplies()->contains($value));

        $response = $this->comment->removeReply($value);

        self::assertInstanceOf(Comment::class, $response);
        self::assertCount(0, $this->comment->getReplies());
        self::assertFalse($this->comment->getReplies()->contains($value));
    }

    /**
     * Ajouter plusieurs Commentaires enfant dans un commentaire parent
     * Afficher plusieurs Commentaires enfant dans un commentaire parent
     * supprimer plusieurs Commentaires enfant dans un commentaire parent.
     */
    public function testComments(): void
    {
        $value = new Comment();
        $value1 = new Comment();
        $value2 = new Comment();

        $response = $this->comment->addReply($value);
        $response1 = $this->comment->addReply($value1);
        $response2 = $this->comment->addReply($value2);

        self::assertInstanceOf(Comment::class, $response);
        self::assertInstanceOf(Comment::class, $response1);
        self::assertInstanceOf(Comment::class, $response2);
        self::assertCount(3, $this->comment->getReplies());
        self::assertTrue($this->comment->getReplies()->contains($value));
        self::assertTrue($this->comment->getReplies()->contains($value1));
        self::assertTrue($this->comment->getReplies()->contains($value2));

        $response = $this->comment->removeReply($value);

        self::assertInstanceOf(Comment::class, $response);
        self::assertCount(2, $this->comment->getReplies());
        self::assertFalse($this->comment->getReplies()->contains($value));
        self::assertTrue($this->comment->getReplies()->contains($value1));
        self::assertTrue($this->comment->getReplies()->contains($value2));
    }

    /**
     * Ajouter pour allier un commentaire a un user
     * Afficher le propriétaire (user) du commentaire.
     */
    public function testUser(): void
    {
        $value = new User();

        $response = $this->comment->setOwner($value);

        self::assertInstanceOf(Comment::class, $response);
        self::assertSame($value, $this->comment->getOwner());
    }
}
