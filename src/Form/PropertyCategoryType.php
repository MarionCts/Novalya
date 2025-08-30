<?php

namespace App\Form;

use App\Entity\PropertyCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PropertyCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PropertyCategory::class,
            'attr' => ['class' => 'form'],
            'required' => false,
            'translation_domain' => 'forms',
        ]);
    }
}
