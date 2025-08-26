<?php

namespace App\Entity;

use App\Enum\ClimateClass;
use App\Enum\EnergyClass;
use App\Enum\Status;
use App\Repository\PropertyRepository;
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

    #[ORM\Column(enumType: EnergyClass::class)]
    private ?EnergyClass $energyClass = null;

    #[ORM\Column(enumType: ClimateClass::class)]
    private ?ClimateClass $climateClass = null;

    #[ORM\Column(length: 255)]
    private ?string $mapUrl = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $price = null;

    #[ORM\Column(enumType: Status::class)]
    private ?Status $status = null;

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
}
