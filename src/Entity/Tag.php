<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Property>
     */
    #[ORM\ManyToMany(targetEntity: Property::class, inversedBy: 'tags')]
    private Collection $property;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nameEn = null;

    public function __construct()
    {
        $this->property = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Property>
     */
    public function getProperty(): Collection
    {
        return $this->property;
    }

    public function addProperty(Property $property): static
    {
        if (!$this->property->contains($property)) {
            $this->property->add($property);
        }

        return $this;
    }

    public function removeProperty(Property $property): static
    {
        $this->property->removeElement($property);

        return $this;
    }

    public function getNameEn(): ?string
    {
        return $this->nameEn;
    }

    public function setNameEn(?string $nameEn): static
    {
        $this->nameEn = $nameEn;

        return $this;
    }

    public function getTranslatedName(string $locale): ?string
    {
        if ($locale === 'en' && $this->getNameEn()) {
            return $this->getNameEn();
        }

        return $this->getName();
    }
}
