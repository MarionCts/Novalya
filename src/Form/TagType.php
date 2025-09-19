<?php

namespace App\Form;

use App\Entity\Property;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'tag.nameFr.label',
                'constraints' => [
                    new NotBlank([
                        'message' => 'property.name.not_blank',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'property.name.length_min',
                        'maxMessage' => 'property.name.length_max',
                        'normalizer' => 'trim',
                    ]),
                ],
            ])
            ->add('nameEn', null, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'tag.nameEn.label',
                'constraints' => [
                    new NotBlank([
                        'message' => 'property.name.not_blank',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'property.name.length_min',
                        'maxMessage' => 'property.name.length_max',
                        'normalizer' => 'trim',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
            'attr' => ['class' => 'form'],
            'required' => false,
            'translation_domain' => 'forms',
        ]);
    }
}
