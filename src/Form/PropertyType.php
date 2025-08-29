<?php

namespace App\Form;

use App\Entity\Property;
use App\Enum\Status;
use App\Enum\ClimateClass;
use App\Enum\EnergyClass;
use App\Entity\PropertyCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setAttribute('translation_domain', 'forms');

        $builder
            ->add('name', null, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.name.label',
            ])
            ->add('city', null, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.city.label',
            ])
            ->add('description', TextareaType::class, [
                'attr'  => ['class' => 'form__input', 'rows' => 8],
                'label' => 'property.description.label',
                'constraints' => [
                    new NotBlank([
                        'message' => 'property.description.not_blank',
                    ]),
                    new Length([
                        'min' => 100,
                        'max' => 5000,
                        'minMessage' => 'property.description.length_min',
                        'maxMessage' => 'property.description.length_max',
                        'normalizer' => 'trim',
                    ]),
                ],
            ])
            ->add('roomsTotal', null, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.rooms_total.label',
            ])
            ->add('bathroomNumber', null, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.bathrooms.label',
            ])
            ->add('bedroomNumber', null, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.bedrooms.label',
            ])
            ->add('indoorSurface', null, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.indoor_surface.label',
            ])
            ->add('outdoorSurface', null, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.outdoor_surface.label',
            ])
            ->add('energyClass', EnumType::class, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.energy_class.label',
                'class' => EnergyClass::class,
                'choice_label' => fn(EnergyClass $c) => 'enum.energy_class.'.$c->name,
                'choice_translation_domain' => 'forms',
            ])
            ->add('climateClass', EnumType::class, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.climate_class.label',
                'class' => ClimateClass::class,
                'choice_label' => fn(ClimateClass $c) => 'enum.climate_class.'.$c->name,
                'choice_translation_domain' => 'forms',
            ])
            ->add('mapUrl', null, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.map_url.label',
                'invalid_message' => 'property.map_url.invalid',
            ])
            ->add('price', null, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.price.label',
            ])
            ->add('status', EnumType::class, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.status.label',
                'class' => Status::class,
                'choice_label' => fn(Status $s) => 'enum.status.'.$s->name,
                'choice_translation_domain' => 'forms',
            ])
            ->add('category', EntityType::class, [
                'class' => PropertyCategory::class,
                'choice_label' => 'name',
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.category.label',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
            'attr' => ['class' => 'form'],
            'required' => false,
            'translation_domain' => 'forms',
        ]);
    }
}
