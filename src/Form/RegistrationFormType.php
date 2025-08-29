<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'attr' => ['class' => 'form__input'],
                'label' => "Nom * :",
            ])
            ->add('firstName', null, [
                'attr' => ['class' => 'form__input'],
                'label' => "Prénom * :",
            ])
            ->add('email', null, [
                'attr' => ['class' => 'form__input'],
                'label' => "Email * :",
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => "Accepter les conditions d'utilisation * :",
                'row_attr' => ['class' => 'agree-terms'],
                'constraints' => [
                    new IsTrue([
                        'message' => "Veuillez agréer aux conditions d'utilisation du site",
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => "Mot de passe * :",
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form__input'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer un mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Le mot de passe doit comporter un minimum de {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
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
        ]);
    }
}
