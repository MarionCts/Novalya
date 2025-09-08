<?php

namespace App\Form;

use App\Entity\Property;
use App\Entity\Tag;
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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints as Assert;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setAttribute('translation_domain', 'forms');

        $builder

            // PROPERTY NAME
            ->add('name', null, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.name.label',
                'constraints' => [
                    new NotBlank([
                        'message' => 'property.name.not_blank',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 200,
                        'minMessage' => 'property.name.length_min',
                        'maxMessage' => 'property.name.length_max',
                        'normalizer' => 'trim',
                    ]),
                ],
            ])

            // PROPERTY PRICE
            ->add('price', IntegerType::class, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.price.label',
                'invalid_message' => 'property.price.number',
                'constraints' => [
                    new NotBlank([
                        'message' => 'property.price.not_blank',
                    ]),
                    new Assert\Positive([
                        'message' => 'property.price.positive',
                    ]),
                    new Assert\Type([
                        'type' => 'integer',
                    ]),
                ],
            ])

            // PROPERTY CATEGORY
            ->add('category', EntityType::class, [
                'class' => PropertyCategory::class,
                'choice_label' => 'name',
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.category.label',
            ])

            // TAGS CATEGORY
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'required' => false,
                'choice_label' => 'name',
                'multiple' => true,
                'by_reference' => false,
                'expanded' => true,
                'attr'  => ['class' => 'form__input checkbox'],
                'label' => 'property.tags.label',
            ])

            // PROPERTY DESCRIPTION
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

            // PROPERTY FEATURED PHOTO

            // Adding another form named "propertyImages" to manage the upload of multiple photos
            ->add('propertyImages', CollectionType::class, [
                'label' => false,
                'entry_type' => PropertyImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])

            // PROPERTY CITY
            ->add('city', null, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.city.label',
                'constraints' => [
                    new NotBlank([
                        'message' => 'property.city.not_blank',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 200,
                        'minMessage' => 'property.city.length_min',
                        'maxMessage' => 'property.city.length_max',
                        'normalizer' => 'trim',
                    ]),
                ],
            ])

            // PROPERTY GOOGLE MAP URL
            ->add('mapUrl', UrlType::class, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.map_url.label',
                'invalid_message' => 'property.map_url.invalid',
                'constraints' => [
                    new NotBlank([
                        'message' => 'property.mapUrl.not_blank',
                    ]),
                    new Assert\Url([
                        'message' => 'property.mapUrl.valid',
                    ]),
                ],
            ])

            // PROPERTY TOTAL ROOMS
            ->add('roomsTotal', IntegerType::class, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.rooms_total.label',
                'invalid_message' => 'property.roomsTotal.number',
                'constraints' => [
                    new NotBlank([
                        'message' => 'property.roomsTotal.not_blank',
                    ]),
                    new Assert\Positive([
                        'message' => 'property.roomsTotal.positive',
                    ]),
                    new Assert\Type([
                        'type' => 'integer',
                    ]),
                ],
            ])

            // PROPERTY BEDROOMS
            ->add('bedroomNumber', IntegerType::class, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.bedrooms.label',
                'invalid_message' => 'property.bedrooms.number',
                'constraints' => [
                    new NotBlank([
                        'message' => 'property.bedrooms.not_blank',
                    ]),
                    new Assert\Positive([
                        'message' => 'property.bedrooms.positive',
                    ]),
                    new Assert\Type([
                        'type' => 'integer',
                    ]),
                ],
            ])

            // PROPERTY BATHROOMS
            ->add('bathroomNumber', IntegerType::class, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.bathrooms.label',
                'invalid_message' => 'property.bathrooms.number',
                'constraints' => [
                    new NotBlank([
                        'message' => 'property.bathrooms.not_blank',
                    ]),
                    new Assert\Positive([
                        'message' => 'property.bathrooms.positive',
                    ]),
                    new Assert\Type([
                        'type' => 'integer',
                    ]),
                ],
            ])

            // PROPERTY INDOOR SURFACE
            ->add('indoorSurface', IntegerType::class, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.indoor_surface.label',
                'invalid_message' => 'property.indoorSurface.number',
                'constraints' => [
                    new NotBlank([
                        'message' => 'property.indoorSurface.not_blank',
                    ]),
                    new Assert\Positive([
                        'message' => 'property.indoorSurface.positive',
                    ]),
                    new Assert\Type([
                        'type' => 'integer',
                    ]),
                ],
            ])

            // PROPERTY OUTDOOR SURFACE
            ->add('outdoorSurface', IntegerType::class, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.outdoor_surface.label',
                'invalid_message' => 'property.outdoorSurface.number',
                'constraints' => [
                    new NotBlank([
                        'message' => 'property.outdoorSurface.not_blank',
                    ]),
                    new Assert\Type([
                        'type' => 'integer',
                    ]),
                ],
            ])

            // PROPERTY ENERGY CLASS
            ->add('energyClass', EnumType::class, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.energy_class.label',
                'class' => EnergyClass::class,
                'choice_label' => fn(EnergyClass $c) => 'enum.energy_class.' . $c->name,
                'choice_translation_domain' => 'forms',
            ])

            // PROPERTY CLIMATE CLASS
            ->add('climateClass', EnumType::class, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.climate_class.label',
                'class' => ClimateClass::class,
                'choice_label' => fn(ClimateClass $c) => 'enum.climate_class.' . $c->name,
                'choice_translation_domain' => 'forms',
            ])

            // PROPERTY PUBLICATION STATUS
            ->add('status', EnumType::class, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.status.label',
                'class' => Status::class,
                'choice_label' => fn(Status $s) => 'enum.status.' . $s->name,
                'choice_translation_domain' => 'forms',
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
