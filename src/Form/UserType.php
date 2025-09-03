<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'attr' => ['class' => 'form__input'],
                'label' => 'register.name.label',
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
                'attr' => ['class' => 'form__input'],
                'label' => 'register.firstName.label',
                'constraints' => [
                    new NotBlank([
                        'message' => 'register.firstName.not_blank',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'register.firstName.length_min',
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('email', null, [
                'attr' => ['class' => 'form__input'],
                'label' => 'register.email.label',
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
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'register.password.newPassword',
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form__input'
                ],
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'minMessage' => 'register.password.length_min',
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()\-_=+{};:,<.>]).*$/',
                        'message' => 'register.password.regex',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => ['class' => 'form'],
            'required' => false,
            'translation_domain' => 'forms',
        ]);
    }
}
