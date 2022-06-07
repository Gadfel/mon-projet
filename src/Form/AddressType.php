<?php

namespace App\Form;
use App\Entity\User;
use App\Repository\AddressRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address',EntityType::class,[
                'required' => true,
                'class' => Address::class,
                'query_builder' => function(AddressRepository $address) {
                    return $address->getAddress($this->getUser());
                },

            'choiceLabel' => 'fullAddress',
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'attr' => [
                    'maxLenght' => 100
                ]
            ])
            ->add('address',TextType::class, [
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

        //     ->add('user',EntityType::class,[
        //                'required' => true,
        //                  'class' => User::class,
        //                  'choice_label' =>'firstName'
        //     ])        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
