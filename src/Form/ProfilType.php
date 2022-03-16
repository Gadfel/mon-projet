<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordStrength;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('firstName', TextType::class, [
                'label'=> 'Nom',
                'required' => true,
                'attr' => [
                    'maxLength' => 50
                ]
            ])
            ->add('lastName', TextType::class, [
                'label'=> 'Prenom', 
                'required' => true,
                'attr' => [
                    'maxLength' => 50
                ]
            ])
            ->add('email', EmailType::class, [
                'label'=> 'Email',
                'required' => true,
                'attr' => [
                    'maxLength' => 180
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                'label'=> 'mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un mot de passe',
                    ]),
                    new PasswordStrength([
                        'minLength' => 8,
                        'tooShortMessage' => 'Le mot de passe doit contenir au moins {{length}} caractères.',
                        'minStrength' => 4,
                        'message' => 'Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule, une chiffre et un caractère spécial'
                    ])
                ],
            ])
            ->add('phone', TextType::class, [
                'label'=> 'telephone',
                'required' => true,
                'attr' => [
                    'maxLength' => 15
                ]

            ])
            ->add('companyName', TextType::class, [
                'required' => true,
                'attr' => [
                    'maxLength' => 100
                ]
            ])
    //         ->add('address1',TextType::class, [
    //             'required' => true,
    //             'attr' => [
    //                 'maxLenght' => 100
    //             ]
    //         ])
    //         ->add('address2',TextType::class, [
    //             'required' => false,
    //             'attr' => [
    //                 'maxLenght' => 100
    //             ]
    //         ])
    //         ->add('postalCode',IntegerType::class,[
    //             'required' => true,
    //             'attr' => [
    //                 'maxLenght' => 10
    //             ]


    //         ])
    //         ->add('city',TextType::class,[
    //             'required' => true, 
    //             'attr' => [
    //                ' maxLenght'=> 20
    //             ]
                
    //             ])
    //         ->add('country', TextType::class,[
    //             'required' => true, 
    //             'attr' => [
    //                ' maxLenght'=> 20
    //             ]

    //             ])
     ;       
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
