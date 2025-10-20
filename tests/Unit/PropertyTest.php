<?php

namespace App\Tests\Entity;

use App\Entity\Property;
use App\Entity\PropertyImage;
use App\Enum\ClimateClass;
use App\Enum\EnergyClass;
use PHPUnit\Framework\TestCase;

class PropertyTest extends TestCase
{
    public function testSettersAndGetters(): void
    {
        $property = new Property();

        $property
            ->setName('Villa Riviera')
            ->setCity('Nice')
            ->setDescription('Belle maison de bord de mer')
            ->setRoomsTotal(5)
            ->setBathroomNumber(2)
            ->setBedroomNumber(3)
            ->setIndoorSurface(150.5)
            ->setOutdoorSurface(300.0)
            ->setPrice('750000')
            ->setMapUrl('https://maps.google.com/example')
            ->setEnergyClass(EnergyClass::A)
            ->setClimateClass(ClimateClass::B)
            ->setCreatedAt(new \DateTime('2025-10-20'));

        $this->assertSame('Villa Riviera', $property->getName());
        $this->assertSame('Nice', $property->getCity());
        $this->assertSame(3, $property->getBedroomNumber());
        $this->assertSame(150.5, $property->getIndoorSurface());
        $this->assertSame(EnergyClass::A, $property->getEnergyClass());
        $this->assertInstanceOf(\DateTime::class, $property->getCreatedAt());
    }

    public function testGetTranslatedDescription(): void
    {
        $property = new Property();

        $property->setDescription('Charmante maison au centre-ville');
        $property->setDescriptionEn('Lovely house downtown');

        $this->assertSame(
            'Lovely house downtown',
            $property->getTranslatedDescription('en')
        );

        $this->assertSame(
            'Charmante maison au centre-ville',
            $property->getTranslatedDescription('fr')
        );

        // Cas où la traduction anglaise n'existe pas
        $property->setDescriptionEn(null);
        $this->assertSame(
            'Charmante maison au centre-ville',
            $property->getTranslatedDescription('en')
        );
    }

    public function testAddAndRemovePropertyImage(): void
    {
        $property = new Property();
        $image = new PropertyImage();

        $property->addPropertyImage($image);
        $this->assertCount(1, $property->getPropertyImages());
        $this->assertSame($property, $image->getProperty());

        $property->removePropertyImage($image);
        $this->assertCount(0, $property->getPropertyImages());
        $this->assertNull($image->getProperty());
    }
}