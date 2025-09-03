<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setAttribute('translation_domain', 'forms');

        $builder

            ->add('name', null, [
                'attr' => ['class' => 'form__input', 'placeholder' => 'contact.plName'],
                'label' => 'contact.plName',
                'label_attr' => [
                    'class' => 'sr-only',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'register.name.not_blank',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'register.name.length_min',
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('firstName', null, [
                'attr' => ['class' => 'form__input', 'placeholder' => 'contact.plFirstName'],
                'label' => 'contact.plFirstName',
                'label_attr' => [
                    'class' => 'sr-only',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'register.name.not_blank',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'register.name.length_min',
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('email', null, [
                'attr' => ['class' => 'form__input', 'placeholder' => 'contact.plEmail'],
                'label' => 'contact.plEmail',
                'label_attr' => [
                    'class' => 'sr-only',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'register.email.not_blank',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                        'message' => "register.email.regex",
                    ]),
                ],
            ])
            ->add('phone', null, [
                'attr' => ['class' => 'form__input', 'placeholder' => 'contact.plPhone'],
                'label' => 'contact.plPhone',
                'label_attr' => [
                    'class' => 'sr-only',
                ],
            ])
            ->add('message', TextareaType::class, [
                'attr'  => ['class' => 'form__input', 'rows' => 8, 'placeholder' => 'contact.plMessage'],
                'label' => 'contact.plMessage',
                'label_attr' => [
                    'class' => 'sr-only',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'property.description.not_blank',
                    ]),
                    new Length([
                        'min' => 50,
                        'max' => 5000,
                        'minMessage' => 'property.description.length_min',
                        'maxMessage' => 'property.description.length_max',
                        'normalizer' => 'trim',
                    ]),
                ],
            ])
            ->add('request', ChoiceType::class, [
                'attr'  => ['class' => 'form__input'],
                'label' => 'contact.plMessage',
                'label_attr' => [
                    'class' => 'sr-only',
                ],
                'choices'  => [
                    'contact.plRequest' =>"contact.plRequest",
                    'contact.optionOne' =>"contact.optionOne",
                    'contact.optionTwo' =>"contact.optionTwo",
                    'contact.optionThree' =>"contact.optionThree",
                ],
                'choice_translation_domain' => 'forms',
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => ['class' => 'form'],
            'required' => false,
            'translation_domain' => 'forms',
        ]);
    }
}
