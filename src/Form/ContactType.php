<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => true,
                'attr' => [
                    'maxLength' => 100
                ]
            ])
            ->add('lastName', TextType::class, [
                'required' => true,
                'attr' => [
                    'maxLength' => 100
                ]
            ])
            ->add('emailAddress', EmailType::class, [
                'required' => true,
                'attr' => [
                    'maxLength' => 100
                ]
            ])
            ->add('phone', TextType::class, [
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
            ->add('message', TextareaType::class, [
                'required' => true,
                'attr' => [
                    'minLength' => 50,
                    'maxLength' => 1000
                ],
                'help' => '1000 caractères maximum'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
