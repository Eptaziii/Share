<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ModifPassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', PasswordType::class, ['attr' => ['class' => 'form-control'], 'label_attr' => ['class' => 'fw-bold pt-2'], 'mapped' => false, 'label' => 'Mot de passe actuel'])
            ->add('newpassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Nouveau mot de passe',
                'mapped' => false,
                'label_attr' => ['class' => 'fw-bold'],
                'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrez un mot de passe',
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Veuillez entrez un mot de passe de {{ limit }} caractères',
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
                        'match' => true,
                        'message' => 'Veuillez ajoutez au moins une majuscule',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/[^a-zA-Z\d]/',
                        'match' => true,
                        'message' => 'Veuillez ajoutez au moins un caractère special',
                    ]),

                ],
            ])
            ->add('confirmnewpassword', PasswordType::class, ['attr' => ['class' => 'form-control'], 'label_attr' => ['class' => 'fw-bold pt-2'], 'mapped' => false, 'label' => 'Confirmer le nouveau mot de passe'])
            ->add('modifier', SubmitType::class, ['attr' => ['class'=> 'btn bg-primary text-white m-4' ],'row_attr' => ['class' => 'text-center'],])
    
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
