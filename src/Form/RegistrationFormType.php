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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;



class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['attr' => ['class'=> 'form-control'], 'label_attr' => ['class'=>'fw-bold']])
            ->add('prenom',TextType::class, ['attr' => ['class'=> 'form-control'], 'label_attr' => ['class'=>'fw-bold'], 
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez mettre votre prenom',
                ]),
                new Length([
                    'min' => 3,
                    'minMessage' => 'Veuillez mettre un prenom de {{ limit }} caractères',
                    // max length allowed by Symfony for security reasons
                    'max' => 50,
                    'maxMessage' => 'Veuillez mettre un prenom moins grand'
                ]),
                new Assert\Regex([
                    'pattern' => '/\d/',
                    'match' => false,
                    'message' => 'Veuillez ne pas mettre de chiffre',
                ]),
                new Assert\Regex([
                    'pattern' => '/[a-z]/',
                    'message' => 'Veuillez ajouter au moins une minuscule',
                ]),
                new Assert\Regex([
                    'pattern' => '/[#?!@$%^&*]/',
                    'match' => false,
                    'message' => 'Votre caractère spécial n\'est pas correcte',
                ]),
            ],
            
            ])

            ->add('nom',TextType::class, ['attr' => ['class'=> 'form-control'], 'label_attr' => ['class'=>'fw-bold'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez mettre votre nom',
                ]),
                new Length([
                    'min' => 3,
                    'minMessage' => 'Veuillez mettre un prenom de {{ limit }} caractères',
                    // max length allowed by Symfony for security reasons
                    'max' => 50,
                    'maxMessage' => 'Veuillez mettre un nom moins grand'
                ]),
                new Assert\Regex([
                    'pattern' => '/\d/',
                    'match' => false,
                    'message' => 'Veuillez ne pas mettre de chiffre',
                ]),
                new Assert\Regex([
                    'pattern' => '/[a-z]/',
                    'message' => 'Veuillez ajouter au moins une minuscule',
                ]),
                new Assert\Regex([
                    'pattern' => '/[#?!@$%^&*]/',
                    'match' => false,
                    'message' => 'Votre caractère spécial n\'est pas correcte',
                ]),
            ],
            
            ])
            ->add('agreeTerms', CheckboxType::class, [
                                'mapped' => false,
                                'data' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Veuillez lire et accepter les termes',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => "Mot de passe",
                'label_attr' => ['class'=> 'fw-bold'],
                'attr' => ['autocomplete' => 'new-password','class'=> 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un mot de passe',
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Veuillez mettre un mot de passe de {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Assert\Regex([
                        'pattern' => '/\d/',
                        'match' => true,
                        'message' => 'Veuillez ajouter au moins un chiffre',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/[A-Z]/',
                        'message' => 'Veuillez ajouter au moins une majuscule',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/[a-z]/',
                        'message' => 'Veuillez ajouter au moins une minuscule',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/[#?!@$%^&*-]/',
                        'message' => 'Veuillez ajouter au moins un caractère spécial',
                    ]),
                ],
            ])
            ->add('verifPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => "Confirmer mot de passe",
                'label_attr' => ['class'=> 'fw-bold'],
                'attr' => ['autocomplete' => 'new-password','class'=> 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un mot de passe',
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Veuillez mettre un mot de passe de {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Assert\Regex([
                        'pattern' => '/\d/',
                        'match' => true,
                        'message' => 'Veuillez ajouter au moins un chiffre',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/[A-Z]/',
                        'message' => 'Veuillez ajouter au moins une majuscule',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/[a-z]/',
                        'message' => 'Veuillez ajouter au moins une minuscule',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/[#?!@$%^&*-]/',
                        'message' => 'Veuillez ajouter au moins un caractère spécial',
                    ]),
                ],
                ])
            ->add('captcha', TextType::class, [
                'mapped' => false,
                
                'attr' => [
                    'class'=> 'form-control',
                ], 
                'label_attr' => [
                    'class'=>'fw-bold'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => ['id' => 'registration_form']
        ]);
    }
}
