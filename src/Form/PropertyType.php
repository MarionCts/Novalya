<?php

namespace App\Form;

use App\Entity\Property;
use App\Entity\PropertyCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('city')
            ->add('description')
            ->add('roomsTotal')
            ->add('bathroomNumber')
            ->add('bedroomNumber')
            ->add('indoorSurface')
            ->add('outdoorSurface')
            ->add('energyClass')
            ->add('climateClass')
            ->add('mapUrl')
            ->add('price')
            ->add('status')
            ->add('createdAt')
            ->add('modifiedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('category', EntityType::class, [
                'class' => PropertyCategory::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}
