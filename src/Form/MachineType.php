<?php

namespace App\Form;

use App\Entity\Machine;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;



class MachineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
              'required'=> true,
              'label'=>'nom',
              'attr' =>[
                'maxlength'=> 25,
                'placeholder'=> 'votre Nom' 
                
                ]
            ])
            ->add('model',TextType::class,[
                'required' =>true,
                'label' =>'model',
                'attr' => [
                    'maxlength'=> 45,
                    'placeholder'=> 'model de Machine'
                ]
            ])
            ->add('img',FileType::class,[
                'required'=> true,
                'label'=> 'photo',
                'mapped'=>false,
                'help'=> 'png , jpg, jpeg, jp2 ou webpb - 1Mo maximum',
                'constraints' =>[
                    new Image([
                        'maxSize'=> '1M',
                        'maxSizeMessage' =>'la photo est trop volumineuse ({{ size }} Mo).Maximum autorisé :{{ limit }} Mo.',
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
            ->add('description', TextareaType::class, [
                'required' => true, //valeur par defaut même si on met pas on le trouve quand même 
                'label' => 'Description',
                'attr' => [
                    'maxlength' => 65535,
                    'placeholder' => 'tres pratique'
                ]

            ])
        //     ->add('product',EntityType::class,[
        //        'required' => true,
        //         'class' => Product::class,
        //         'choice_label' =>'name'
        //    ])        
             /*->add(user)*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Machine::class,
        ]);
    }
}