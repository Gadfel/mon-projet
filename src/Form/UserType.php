<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordStrength;

class UserType extends AbstractType
{
     public function buildForm(FormBuilderInterface $builder, array $options): void
     {
         $builder

             ->add('firstName', TextType::class, [
                 'required' => true,
                 'attr' => [
                     'maxLength' => 50
                 ]
             ])
             ->add('lastName', TextType::class, [
                 'required' => true,
                 'attr' => [
                     'maxLength' => 50
                 ]
             ])
            //  ->add('roles', ChoiceType::class, [
            //      'required' => true,
            //      'multiple' => true,
            //      'expanded' => true,
            //      'choices' => [
            //          'Administrateur' => 'ROLE_ADMIN',
            //          'Utilisateur' => 'ROLE_USER',

            //      ],

            //  ])
             ->add('email', EmailType::class, [
                 'required' => true,
                 'attr' => [
                     'maxLength' => 180
                 ]
             ])

             ->add('plainPassword', PasswordType::class, [
                'required'=> false,
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
                 'required' => true,
                 'attr' => [
                     'maxLength' => 15
                 ]

             ])
             ->add('company_name', TextType::class, [
                 'required' => true,
                 'attr' => [
                     'maxLength' => 100
                 ]
             ]);
     }

     public function configureOptions(OptionsResolver $resolver): void
     {
         $resolver->setDefaults([
             'data_class' => User::class,
         ]);
     }
}
