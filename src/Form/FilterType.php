<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Enum\Value;
use App\Entity\PropertyCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setAttribute('translation_domain', 'forms');
        $choices = $options['cities'] ?? [];

        $builder

            // FILTER VALUE
            ->add('value', EnumType::class, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.value.label',
                'class' => Value::class,
                'placeholder' => 'property.value.placeholder',
                'choice_label' => fn(Value $c) => 'enum.value.' . $c->name,
                'choice_translation_domain' => 'forms',
                'label_attr' => [
                    'class' => 'sr-only',
                ],
            ])

            // FILTER CATEGORY
            ->add('category', EntityType::class, [
                'class' => PropertyCategory::class,
                'choice_label' => 'name',
                'placeholder' => 'property.category.placeholder',
                'attr'  => ['class' => 'form__input'],
                'label' => 'property.category.label',
                'label_attr' => [
                    'class' => 'sr-only',
                ],
            ])

            // FILTER LOCATION
            ->add('city', ChoiceType::class, [
                'choices' => $choices,
                'placeholder' => 'property.city.placeholder',
                'attr'  => ['class' => 'form__input'],
                'required' => false,
                'label_attr' => [
                    'class' => 'sr-only',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'method' => 'GET',
            'attr' => ['class' => 'form'],
            'required' => false,
            'cities' => [],
            'translation_domain' => 'forms',
        ]);
    }
}
