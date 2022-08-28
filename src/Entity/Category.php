<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=StorageSpace::class, mappedBy="category", orphanRemoval=true)
     */
    private $storageSpaces;

    public function __construct()
    {
        $this->storageSpaces = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $storageSpace->setCategory($this);
        }

        return $this;
    }

    public function removeStorageSpace(StorageSpace $storageSpace): self
    {
        if ($this->storageSpaces->removeElement($storageSpace)) {
            // set the owning side to null (unless already changed)
            if ($storageSpace->getCategory() === $this) {
                $storageSpace->setCategory(null);
            }
        }

        return $this;
    }
}
