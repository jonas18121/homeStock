<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Email(
     *      message="Votre email '{{ value }}' n'est pas valide, voici un exemple : xxxx@xxxx.xxx"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * exemple : -aA1poiuy
     * 
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @Assert\Regex(
     *      pattern= "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&-_])[A-Za-z\d@$!%*?&-_]{8,10}$/",
     *      message="Votre mot de passe doit avoir minimum 8 et maximum 10 caractères, au moins une lettre majuscule, au moins une lettre minuscule, au moins un chiffre et un caractère spécial qui sont : @ $ ! % * ? & - _" 
     * )
     */
    private $password;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\EqualTo(
     *      propertyPath="password", 
     *      message="Les 2 mots de passe doîvent être identiques"
     * )
     */
    private $confirm_password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min=2,
     *      max=70,
     *      minMessage="Votre nom '{{ value }}' doit comporter au moins {{ limit }} caractères",
     *      maxMessage="Votre nom '{{ value }}' ne peut pas dépasser {{ limit }} caractères"
     * )
     * @Assert\Regex(
     *      pattern="/^[a-zA-Z ]+([-]{0,1})[a-zA-Z ]+$/",
     *      message="Votre nom '{{ value }}' doit contenir uniquement des lettres et une fois ce caractère pour les noms composés : - "
     * )
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min=2,
     *      max=70,
     *      minMessage="Votre prénom '{{ value }}' doit comporter au moins {{ limit }} caractères",
     *      maxMessage="Votre prénom '{{ value }}' ne peut pas dépasser {{ limit }} caractères"
     * )
     * @Assert\Regex(
     *      pattern="/^[a-zA-Z ]+([-]{0,1})[a-zA-Z ]+$/",
     *      message="Votre prénom '{{ value }}' doit contenir uniquement des lettres et une fois ce caractère pour les noms composés : - "
     * )
     */
    private $firstName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateUpdateAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity=StorageSpace::class, mappedBy="owner", orphanRemoval=true)
     */
    private $storageSpaces;

    /**
     * @ORM\OneToMany(targetEntity=Booking::class, mappedBy="lodger", orphanRemoval=true)
     */
    private $bookings;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="owner", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $customerId;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length(
     *      min=9,
     *      max=10,
     *      minMessage="Votre numéro de téléphone '{{ value }}' doit comporter au moins {{ limit }} chiffres, exemple : 690223344",
     *      maxMessage="Votre numéro de téléphone '{{ value }}' ne peut pas dépasser {{ limit }} chiffres, exemple : 0690223344"
     * )
     * @Assert\Regex(
     *      pattern= "/^[0-9]+$/",
     *      message="Votre numéro de téléphone '{{ value }}' doit contenir uniquement des chiffres et pas d'espace entre les chiffres"
     * )
     * 
     */
    private $phoneNumber;

    public function __construct()
    {
        $this->storageSpaces = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getLastName() . ' ' . $this->getFirstName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

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

    public function getDateUpdateAt(): ?\DateTimeInterface
    {
        return $this->dateUpdateAt;
    }

    public function setDateUpdateAt(?\DateTimeInterface $dateUpdateAt): self
    {
        $this->dateUpdateAt = $dateUpdateAt;

        return $this;
    }

    public function getImages(): ?string
    {
        return $this->images;
    }

    public function setImages(?string $images): self
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @return Collection|StorageSpace[]
     */
    public function getStorageSpaces(): Collection
    {
        return $this->storageSpaces;
    }

    public function addStorageSpace(StorageSpace $storageSpace): self
    {
        if (!$this->storageSpaces->contains($storageSpace)) {
            $this->storageSpaces[] = $storageSpace;
            $storageSpace->setOwner($this);
        }

        return $this;
    }

    public function removeStorageSpace(StorageSpace $storageSpace): self
    {
        if ($this->storageSpaces->removeElement($storageSpace)) {
            // set the owning side to null (unless already changed)
            if ($storageSpace->getOwner() === $this) {
                $storageSpace->setOwner(null);
            }
        }

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
            $booking->setLodger($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getLodger() === $this) {
                $booking->setLodger(null);
            }
        }

        return $this;
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
            $comment->setOwner($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getOwner() === $this) {
                $comment->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of confirm_password
     */ 
    public function getConfirmPassword()
    {
        return $this->confirm_password;
    }

    /**
     * Set the value of confirm_password
     *
     * @return  self
     */ 
    public function setConfirmPassword($confirm_password)
    {
        $this->confirm_password = $confirm_password;

        return $this;
    }

    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    public function setCustomerId(?string $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }
}













/*

* @Assert\Regex(
     *      pattern  = "/^[a-zA-Z0-9_]+@[a-z]{4,7}.[a-z]{2,3}$/",
     *      message="Votre email n'est pas valide, voici un exemple : xxxx@xxxx.xxx"
     * )


*/