<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait DateTimeTrait
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTime $created_at = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTime $updated_at = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTime $deleted_at = null;

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTime $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getDeletedAt(): ?\DateTime
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?\DateTime $deleted_at): self
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt((new \DateTime('now'))->setTimezone(new \DateTimeZone('UTC')));
        if (null === $this->getCreatedAt()) {
            $this->setCreatedAt((new \DateTime('now'))->setTimezone(new \DateTimeZone('UTC')));
        }
    }

    public function isDeleted(): bool
    {
        return null !== $this->getDeletedAt();
    }
}
