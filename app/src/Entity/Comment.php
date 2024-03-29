<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Entity\Traits\DateTimeTrait;
use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    use DateTimeTrait;

    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank
     */
    private ?string $content;

    /**
     * @ORM\ManyToOne(targetEntity=StorageSpace::class, inversedBy="comments")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private ?StorageSpace $storageSpace;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $owner;

    /**
     * Représente le commentaire parent, si un user a répondu à ce commentaire.
     *
     * @ORM\ManyToOne(targetEntity=Comment::class, inversedBy="replies")
     */
    private ?Comment $parent;

    /**
     * Représente les commentaires enfants (réponses) d'un commentaire parent
     * si un ou plusieurs users répondents à un commentaire précis.
     *
     * Dans la BDD, la table Comment aura comment.id qui sera en relation avec comment.parent_id
     *
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="parent", orphanRemoval=true)
     *
     * @var Comment[]|Collection<int, Comment>
     */
    private $replies;

    public function __construct()
    {
        $this->replies = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getStorageSpace(): ?StorageSpace
    {
        return $this->storageSpace;
    }

    public function setStorageSpace(?StorageSpace $storageSpace): self
    {
        $this->storageSpace = $storageSpace;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return self[]|Collection<int, self>
     */
    public function getReplies()
    {
        return $this->replies;
    }

    public function addReply(self $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $this->replies[] = $reply;
            $reply->setParent($this);
        }

        return $this;
    }

    public function removeReply(self $reply): self
    {
        if ($this->replies->removeElement($reply)) {
            // set the owning side to null (unless already changed)
            if ($reply->getParent() === $this) {
                $reply->setParent(null);
            }
        }

        return $this;
    }
}
