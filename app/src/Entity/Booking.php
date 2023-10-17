<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\DateTimeTrait;
use App\Repository\BookingRepository;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 */
class Booking
{
    use DateTimeTrait;
    
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lodger;

    /**
     * @ORM\ManyToOne(targetEntity=StorageSpace::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $storageSpace;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateEndAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $finish = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pay = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $checkForPayement = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripeSessionId;

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

    public function getLodger(): ?User
    {
        return $this->lodger;
    }

    public function setLodger(?User $lodger): self
    {
        $this->lodger = $lodger;

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

    public function getDateEndAt(): ?\DateTimeInterface
    {
        return $this->dateEndAt;
    }

    public function setDateEndAt(\DateTimeInterface $dateEndAt): self
    {
        $this->dateEndAt = $dateEndAt;

        return $this;
    }

    public function getFinish(): ?bool
    {
        return $this->finish;
    }

    public function setFinish(?bool $finish): self
    {
        $this->finish = $finish;

        return $this;
    }

    public function getPay(): ?bool
    {
        return $this->pay;
    }

    public function setPay(bool $pay): self
    {
        $this->pay = $pay;

        return $this;
    }

    public function getCheckForPayement(): ?bool
    {
        return $this->checkForPayement;
    }

    public function setCheckForPayement(bool $checkForPayement): self
    {
        $this->checkForPayement = $checkForPayement;

        return $this;
    }

    public function getStripeSessionId(): ?string
    {
        return $this->stripeSessionId;
    }

    public function setStripeSessionId(?string $stripeSessionId): self
    {
        $this->stripeSessionId = $stripeSessionId;

        return $this;
    }
}
