<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateStartAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreatedAt;

    /**
     * @ORM\OneToOne(targetEntity=StorageSpace::class, mappedBy="booking", cascade={"persist", "remove"})
     */
    private $storageSpace;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lodger;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateStartAt(): ?\DateTimeInterface
    {
        return $this->dateStartAt;
    }

    public function setDateStartAt(\DateTimeInterface $dateStartAt): self
    {
        $this->dateStartAt = $dateStartAt;

        return $this;
    }

    public function getDateCreatedAt(): ?\DateTimeInterface
    {
        return $this->dateCreatedAt;
    }

    public function setDateCreatedAt(\DateTimeInterface $dateCreatedAt): self
    {
        $this->dateCreatedAt = $dateCreatedAt;

        return $this;
    }

    public function getStorageSpace(): ?StorageSpace
    {
        return $this->storageSpace;
    }

    public function setStorageSpace(?StorageSpace $storageSpace): self
    {
        // unset the owning side of the relation if necessary
        if ($storageSpace === null && $this->storageSpace !== null) {
            $this->storageSpace->setBooking(null);
        }

        // set the owning side of the relation if necessary
        if ($storageSpace !== null && $storageSpace->getBooking() !== $this) {
            $storageSpace->setBooking($this);
        }

        $this->storageSpace = $storageSpace;

        return $this;
    }

    public function getLodger(): ?User
    {
        return $this->lodger;
    }

    public function setLodger(?User $lodger): self
    {
        $this->lodger = $lodger;

        return $this;
    }
}
