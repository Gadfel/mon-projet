<?php

namespace App\Form;

use App\Repository\AddressRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressCartType extends AbstractType
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

                'choice_label' => 'fullAddress',
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
