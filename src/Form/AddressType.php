<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'attr' => [
                    'maxLenght' => 100
                ]
            ])
            ->add('address1',TextType::class, [
                'required' => true,
                'attr' => [
                    'maxLenght' => 100
                ]
            ])
            ->add('address2',TextType::class, [
                'required' => true,
                'attr' => [
                    'maxLenght' => 100
                ]
            ])
           ->add('postalCode',IntegerType::class,[
                'required' => true,
                'attr' => [
                    'maxLenght' => 10
                ]


            ])
            ->add('city',TextType::class,[
                'required' => true, 
                'attr' => [
                   ' maxLenght'=> 20
                ]
                
                ])
            ->add('country', TextType::class,[
                'required' => true, 
                'attr' => [
                   ' maxLenght'=> 20
                ]

            ])

            ->add('user',EntityType::class,[
                       'required' => true,
                         'class' => user::class,
                         'choice_label' =>'name'
            ])        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
