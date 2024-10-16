<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, ['attr' => ['class'=> 'form-control'], 'label_attr' => ['class'=>'fw-bold'], 
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez mettre votre nom',
                ]),
                new Length([
                    'min' => 3,
                    'minMessage' => 'Votre nom doit avoir une limite de {{ limit }} caractères',
                    // max length allowed by Symfony for security reasons
                    'max' => 30,
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
            ->add('sujet',TextType::class, ['attr' => ['class'=> 'form-control'], 'label_attr' => ['class'=>'fw-bold'], 
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez mettre le sujet',
                ]),
                new Length([
                    'min' => 3,
                    'minMessage' => 'Votre sujet doit avoir une limite de {{ limit }} caractères',
                    // max length allowed by Symfony for security reasons
                    'max' => 50,
                    'maxMessage' => 'Veuillez mettre un sujet moins grand'
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
            ->add('email',EmailType::class, ['attr' => ['class'=> 'form-control'], 'label_attr' => ['class'=>'fw-bold']])
            ->add('message',TextareaType::class, ['attr' => ['class'=> 'form-control', 'rows'=>'7', 'cols'=> '7'], 'label_attr' => ['class'=> 'fw-bold']])
            ->add('envoyer', SubmitType::class, ['attr' => ['class'=> 'btn bg-primary text-white m-4' ],'row_attr' => ['class' => 'text-center'],])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
