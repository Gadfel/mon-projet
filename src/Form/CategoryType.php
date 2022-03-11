<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'titre',
                'attr' => [
                    'maxlength' => 25,
                    'placeholder' => 'choisissez votre boisson'
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => true, //valeur par defaut même si on met pas on le trouve quand même 
                'label' => 'Description',
                'attr' => [
                    'maxlength' => 65535,
                    'placeholder' => ' '
                ]

            ])
            ->add('img', FileType::class, [
                'required' => true,
                'label' => 'photo',
                'mapped' => false,
                'help' => 'png , jpg , jpeg , jp2 ou webpb - 1 Mo maximum',
                'constraints' => [
                    new Image([
                        'maxSize' => '1M',
                        'maxSizeMessage' => '',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                            'image/jp2',
                            'image/wepb'
                        ],
                        'mimeTypesMessage' => 'Merci de sélectionner une img pnp,jpg,jpeg,jp2,webp',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}