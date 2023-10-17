<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\DateTimeTrait;
use App\Repository\StorageSpaceRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=StorageSpaceRepository::class)
 * @Vich\Uploadable
 */
class StorageSpace
{
    use DateTimeTrait;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min=2,
     *      max=70,
     *      minMessage="Le titre de votre espace de stockage '{{ value }}' doit comporter au moins {{ limit }} caractères",
     *      maxMessage="Le titre de votre espace de stockage '{{ value }}' ne peut pas dépasser {{ limit }} caractères"
     * )
     * @Assert\Regex(
     *      pattern="/^[^<>]+$/",
     *      message="Le titre '{{ value }}' de votre espace de stockage n'accepte pas les caractères < et >"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Regex(
     *      pattern="/^[^<>]+$/",
     *      message="Le champ description '{{ value }}' de votre espace de stockage n'accepte pas les caractères < et > "
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Regex(
     *      pattern="/^[\p{L}\p{N}\s,.-_]+$/u",
     *      message="L'adresse de la ville '{{ value }}' de votre espace de stockage doit contenir uniquement des lettres et des chiffres, exemple : 1 rue du Faubourg Saint-Honoré ok"
     * )
     */
    private $adresse;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $space;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank
     * @Assert\Regex(
     *      pattern="/^[0-9.,]+$/",
     *      htmlPattern="[0-9.,]+",
     *      message="Le prix par jour de votre espace de stockage doit contenir uniquement des nombres entier ou des nombres décimaux"
     * )
     */
    private $priceByDays;

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
     * @Assert\NotBlank
     * @Assert\Regex(
     *      pattern= "/^(?!.*-^)(?!.*-$)[\p{L}\s-]+$/",
     *      message="Le nom de la ville '{{ value }}' de votre espace de stockage doit contenir uniquement des lettres et peut contenir un tiret pour les mots composés : - "
     * )
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min=5,
     *      max=5,
     *      minMessage="Le numéro de code postale '{{ value }}' doit comporter au moins {{ limit }} chiffres, exemple : 22000",
     *      maxMessage="Le numéro de code postale '{{ value }}' ne peut pas dépasser {{ limit }} chiffres, exemple : 22000"
     * )
     * @Assert\Regex(
     *      pattern= "/^[0-9]+$/",
     *      message="Le numéro de code postale '{{ value }}' de votre espace de stockage doit contenir uniquement des chiffres et pas d'espace entre les chiffres"
     * )
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
     * @Assert\NotBlank
     */
    private $category;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTitle() . ', ' . $this->getAdresse()  . ' ' . $this->getCity();
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
        // Il est obligatoire qu'au moins un champ change si vous utilisez Doctrine,
        // sinon les écouteurs d'événement ne seront pas appelés et le fichier est perdu
        if ($image) {
            // si 'updatedAt' n'est pas défini dans votre entité, utilisez une autre propriété
            $this->updated_at = new \DateTime('now');
        }
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
