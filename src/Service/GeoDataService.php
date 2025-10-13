

<!-- project_root/
├─ config/
│  ├─ data/
│  │  ├─ departements.json
│  │  └─ regions.json -->

<!-- src/Form/ContactType.php -->
<?php

namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;

class GeoDataService
{
    private string $dataPath;

    public function __construct(KernelInterface $kernel)
    {
        $this->dataPath = $kernel->getProjectDir() . '/config/data';
    }

    public function getDepartements(): array
    {
        $file = $this->dataPath . '/departements.json';
        $content = json_decode(file_get_contents($file), true);

        // Exemple : chaque département contient {"code": "75", "nom": "Paris", ...}
        return array_column($content, 'code');
    }

    public function getRegions(): array
    {
        $file = $this->dataPath . '/regions.json';
        $content = json_decode(file_get_contents($file), true);

        // Exemple : chaque région contient {"nom": "Île-de-France", "code": "11", ...}
        return array_column($content, 'nom');
    }
}

// Dans l'entité Contact

#[ORM\Entity]
class Contact
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $region = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $departement = null;

    // ... getters et setters
}

// src/Form/ContactType.php
<?php

namespace App\Form;

use App\Entity\Contact;
use App\Service\GeoDataService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    private GeoDataService $geoDataService;

    public function __construct(GeoDataService $geoDataService)
    {
        $this->geoDataService = $geoDataService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('departement', ChoiceType::class, [
                'choices' => array_combine(
                    $this->geoDataService->getDepartements(),
                    $this->geoDataService->getDepartements()
                ),
                'placeholder' => 'Choisir un département',
                'label' => 'Département',
            ])
            ->add('region', ChoiceType::class, [
                'choices' => array_combine(
                    $this->geoDataService->getRegions(),
                    $this->geoDataService->getRegions()
                ),
                'placeholder' => 'Choisir une région',
                'label' => 'Région',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}