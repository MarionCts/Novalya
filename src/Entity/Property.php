<?php

namespace App\Entity;

use App\Enum\ClimateClass;
use App\Enum\EnergyClass;
use App\Enum\Status;
use App\Enum\Value;
use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
class Property
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $roomsTotal = null;

    #[ORM\Column]
    private ?int $bathroomNumber = null;

    #[ORM\Column]
    private ?int $bedroomNumber = null;

    #[ORM\Column]
    private ?float $indoorSurface = null;

    #[ORM\Column]
    private ?float $outdoorSurface = null;

    #[ORM\Column(type: 'string', enumType: EnergyClass::class)]
    private ?EnergyClass $energyClass = null;

    #[ORM\Column(type: 'string', enumType: ClimateClass::class)]
    private ?ClimateClass $climateClass = null;

    #[ORM\Column(type: 'text')]
    private ?string $mapUrl = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $price = null;

    #[ORM\Column(type: 'string', enumType: Status::class)]
    private ?Status $status = null;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $modifiedAt = null;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    #[ORM\JoinColumn(nullable: true, onDelete: "CASCADE")]
    private ?PropertyCategory $category = null;

    /**
     * @var Collection<int, Favorite>
     */
    #[ORM\OneToMany(targetEntity: Favorite::class, mappedBy: 'property')]
    private Collection $favorites;

    /**
     * @var Collection<int, PropertyImage>
     */
    #[ORM\OneToMany(targetEntity: PropertyImage::class, mappedBy: 'property', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $propertyImages;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'property')]
    private Collection $tags;

    #[ORM\Column(enumType: Value::class)]
    private ?Value $value = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description_en = null;

    public function __construct()
    {
        $this->favorites = new ArrayCollection();
        $this->propertyImages = new ArrayCollection();
        $this->tags = new ArrayCollection();
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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getRoomsTotal(): ?int
    {
        return $this->roomsTotal;
    }

    public function setRoomsTotal(int $roomsTotal): static
    {
        $this->roomsTotal = $roomsTotal;

        return $this;
    }

    public function getBathroomNumber(): ?int
    {
        return $this->bathroomNumber;
    }

    public function setBathroomNumber(int $bathroomNumber): static
    {
        $this->bathroomNumber = $bathroomNumber;

        return $this;
    }

    public function getBedroomNumber(): ?int
    {
        return $this->bedroomNumber;
    }

    public function setBedroomNumber(int $bedroomNumber): static
    {
        $this->bedroomNumber = $bedroomNumber;

        return $this;
    }

    public function getIndoorSurface(): ?float
    {
        return $this->indoorSurface;
    }

    public function setIndoorSurface(float $indoorSurface): static
    {
        $this->indoorSurface = $indoorSurface;

        return $this;
    }

    public function getOutdoorSurface(): ?float
    {
        return $this->outdoorSurface;
    }

    public function setOutdoorSurface(float $outdoorSurface): static
    {
        $this->outdoorSurface = $outdoorSurface;

        return $this;
    }

    public function getEnergyClass(): ?EnergyClass
    {
        return $this->energyClass;
    }

    public function setEnergyClass(EnergyClass $energyClass): static
    {
        $this->energyClass = $energyClass;

        return $this;
    }

    public function getClimateClass(): ?ClimateClass
    {
        return $this->climateClass;
    }

    public function setClimateClass(ClimateClass $climateClass): static
    {
        $this->climateClass = $climateClass;

        return $this;
    }

    public function getMapUrl(): ?string
    {
        return $this->mapUrl;
    }

    public function setMapUrl(string $mapUrl): static
    {
        $this->mapUrl = $mapUrl;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeImmutable
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(?\DateTimeImmutable $modifiedAt): static
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    public function getCategory(): ?PropertyCategory
    {
        return $this->category;
    }

    public function setCategory(?PropertyCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Favorite>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): static
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->setProperty($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getProperty() === $this) {
                $favorite->setProperty(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PropertyImage>
     */
    public function getPropertyImages(): Collection
    {
        return $this->propertyImages;
    }

    public function addPropertyImage(PropertyImage $propertyImage): static
    {
        if (!$this->propertyImages->contains($propertyImage)) {
            $this->propertyImages->add($propertyImage);
            $propertyImage->setProperty($this);
        }

        return $this;
    }

    public function removePropertyImage(PropertyImage $propertyImage): static
    {
        if ($this->propertyImages->removeElement($propertyImage)) {
            // set the owning side to null (unless already changed)
            if ($propertyImage->getProperty() === $this) {
                $propertyImage->setProperty(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addProperty($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeProperty($this);
        }

        return $this;
    }

    public function getValue(): ?Value
    {
        return $this->value;
    }

    public function setValue(Value $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getDescriptionEn(): ?string
    {
        return $this->description_en;
    }

    public function setDescriptionEn(?string $description_en): static
    {
        $this->description_en = $description_en;

        return $this;
    }

    public function getTranslatedDescription(string $locale): ?string
    {
        if ($locale === 'en' && $this->getDescriptionEn()) {
            return $this->getDescriptionEn();
        }

        return $this->getDescription();
    }
}
