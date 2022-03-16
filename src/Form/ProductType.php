<?php

namespace App\Form;

use App\Entity\Machine;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\OrderLigne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'nom',
                'attr' => [
                    'maxlength' => 25,
                    'placeholder' => 'votre Nom'

                ]
            ])
            ->add('price', IntegerType::class, [
                'required' => true,
                'label' => 'Prix (€)',
                'attr' => [
                    'min' => 1,
                    'max' => 9999999,
                    'placeholder' => '100€'
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => true, //valeur par defaut même si on met pas on le trouve quand même 
                'label' => 'Description',
                'attr' => [
                    'maxlength' => 65535,
                    'placeholder' => 'tres pratique'
                ]

            ])
            ->add('quantity', IntegerType::class, [
                'required' => true,
                'label' => 'quantité',
                'attr' => [
                    'maxlength' => 225,
                    'placeholder' => '',
                ]
            ])
            ->add('img', FileType::class, [
                'required' => true,
                'label' => 'photo',
                'mapped' => false,
                'help' => 'png , jpg, jpeg, jp2 ou webpb - 1Mo maximum',
                'constraints' => [
                    new Image([
                        'maxSize' => '1M',
                        'maxSizeMessage' => 'la photo est trop volumineuse ({{ size }} Mo).Maximum autorisé :{{ limit }} Mo.',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                            'image/jp2',
                            'image/wepb'
                        ],
                        'mimeTypesMessage' => 'Merci de sélectionner une photo pnp,jpg,jpeg,jp2,webp',



                    ])
                ]
            ])
            // ->add('Order_ligne', EntityType::class,[
            //     'required'=> true,
            //      'class'=> OrderLigne::class,
            //      'choice_label' => 'quantity'
            // ])
            ->add('category', EntityType::class, [
                'required' => true,
                'class' => Category::class,
                'choice_label' => 'title'
            ])
            ->add('machine', EntityType::class, [
                'required' => true,
                'class' => Machine::class,
                'choice_label' => 'name'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
