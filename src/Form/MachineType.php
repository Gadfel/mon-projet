<?php

namespace App\Form;

use App\Entity\Machine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class MachineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',IntegerType::class,[
              'require'=> true,
              'label'=>'nom',
              'attr' =>[
                'maxlength'=> 10,
                'placeholder'=> 'votre Nom' 
                
                ]
            ])
            ->add('model',IntegerType::class,[
                'require' =>true,
                'label' =>'model',
                'attr' => [
                    'maxlength'=> 45,
                    'placeholder'=> 'model de Machine'
                ]
            ])
            ->add('img',FileType::class,[
                'require'=> true,
                'label'=> 'photo',
                'mapped'=>false,
                'help'=> 'png , jpg, jpeg, jp2 ou webpb - 1Mo maximum',
                'constraints' =>[
                    new Image([
                        'maxSize'=> '1M',
                        'maxSizeMessage' =>'la photo est trop volumineuse ({{ size }} Mo).Maximum autorisé :{{ limit }} Mo.',
                       /* 'mimeType' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                            'image/jp2',
                            'image/wepb'
                        ],*/
                        'mimeTypesMessage' => 'Merci de sélectionner une photo pnp,jpg,jpeg,jp2,webp', 
                        
                        
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => true, //valeur par defaut même si on met pas on le trouve quand même 
                'label' => 'Description',
                'attr' => [
                    'maxlength' => 65535,
                    'placeholder' => 'Ex. maison de Compagne '
                ]

            ])
           /* ->add         
              ->add*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Machine::class,
        ]);
    }
}