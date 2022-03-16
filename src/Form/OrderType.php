<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date',DateType::class,[
                'required' => true,
                'label' => 'Date',
                'attr' => [
                    'maxlength'=>10

                ]  
                
            ])
            ->add('total_amount',IntegerType::class,[
                'required' => true,
                'label' => 'montant total (€)',
                'attr' => [
                   'min' => 200,
                   'max' => 9999999,

                ]
            ])
            ->add('user',EntityType::class,[
                'required' => true,
                  'class' => User::class,
                  'choice_label' =>'firstName'
            ])     
            ->add('address',EntityType::class,[
                'required' => true,
                'class' => Address::class,
                'choice_label' =>'address'
            ])
            ->add('OrderLigne')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
