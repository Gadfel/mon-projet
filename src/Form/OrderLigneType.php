<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\OrderLigne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class OrderLigneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', IntegerType::class,[
                'required' => true,
                'label' => 'quantité',
                'attr' => [
                    'min' => 1,
                    'max' => 9999999,
                ]
            ])
            ->add('product',EntityType::class,[
                'required' => true,
                'class' => Product::class,
                'choice_label' => 'name'


            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderLigne::class,
        ]);
    }
}
