<?php

namespace App\Entity;

use App\Repository\StorageSpaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=StorageSpaceRepository::class)
 * @Vich\Uploadable
 */
class StorageSpace
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    

    /**
     * @ORM\Column(type="integer")
     */
    private $space;

    /**
     * @ORM\Column(type="float")
     */
    private $priceByDays;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $available;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $images;

    /**
     * @Vich\UploadableField(mapping="images_in_vich_uploade", fileNameProperty="images")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="storageSpace", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="storageSpaces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $postalCode;

    /**
     * @ORM\OneToMany(targetEntity=Booking::class, mappedBy="storageSpace", orphanRemoval=true)
     */
    private $bookings;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $priceByMonth;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="storageSpaces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getSpace(): ?int
    {
        return $this->space;
    }

    public function setSpace(int $space): self
    {
        $this->space = $space;

        return $this;
    }

    public function getPriceByDays(): ?float
    {
        return $this->priceByDays;
    }

    public function setPriceByDays(float $priceByDays): self
    {
        $this->priceByDays = $priceByDays;

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

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function getImages()
    {
        return $this->images;
    }

    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        // if ($image) {
        //     // if 'updatedAt' is not defined in your entity, use another property
        //     $this->updated_at = new \DateTime('now');
        // }
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setStorageSpace($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getStorageSpace() === $this) {
                $comment->setStorageSpace(null);
            }
        }

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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setStorageSpace($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getStorageSpace() === $this) {
                $booking->setStorageSpace(null);
            }
        }

        return $this;
    }

    public function getPriceByMonth(): ?float
    {
        return $this->priceByMonth;
    }

    public function setPriceByMonth(?float $priceByMonth): self
    {
        $this->priceByMonth = $priceByMonth;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
