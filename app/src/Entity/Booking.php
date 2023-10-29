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
use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 */
class Booking
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
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $dateStartAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $lodger;

    /**
     * @ORM\ManyToOne(targetEntity=StorageSpace::class, inversedBy="bookings")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private ?StorageSpace $storageSpace;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTime $dateEndAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $finish = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $pay = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $checkForPayement = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $stripeSessionId;

    public function getId(): int
    {
        return $this->id;
    }

    public function getDateStartAt(): ?\DateTime
    {
        return $this->dateStartAt;
    }

    public function setDateStartAt(?\DateTime $dateStartAt): self
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

    public function getDateEndAt(): ?\DateTime
    {
        return $this->dateEndAt;
    }

    public function setDateEndAt(?\DateTime $dateEndAt): self
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
