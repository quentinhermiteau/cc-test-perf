<?php

namespace App\Form;

use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('niveau', ChoiceType::class, ['choices'=>array_flip(Trick::NIVEAU), 'label' => 'Niveau de difficulté'])
            ->add('trick_group', ChoiceType::class, ['choices'=>array_flip(Trick::TRICK_GROUP), 'label' => 'Type de figure'])
            ->add('imgDocs', CollectionType::class, [
               'entry_type' => FileType::class,
               'entry_options' => array(
                'constraints'  => array(
                  new File(['maxSize' => '2M',
                  "maxSizeMessage" => "Votre document ne doit pas dépasser les 2 Mo.",
                  "mimeTypes" => ["image/jpeg", "image/png"],
                  "mimeTypesMessage" => "Le document doit avoir une des extensions suivantes : jpeg, png, jpg.",
                        ]),
                   ),
                ),
               'prototype_name' => '__image__',
               'prototype'      => true,
               'allow_add'     => true,
               'allow_delete'   => true,
               'required' =>false,
               'label' => false,
               
            ])
            ->add('videoDocs', CollectionType::class, [
               'entry_type' => FileType::class,
               'entry_options' => array(
                'constraints'  => array(
                  new File(['maxSize' => '4M',
                  "maxSizeMessage" => "Votre video ne doit pas dépasser les 20 Mo.",
                  "mimeTypes" => [" video/x-msvideo", "video/mp4","video/ogg"],
                  "mimeTypesMessage" => "Le document doit avoir une des extensions suivantes : avi, mp4, ogg.",
                        ]),
                   ),
                ),
               'prototype_name' => '__video__',
               'prototype'      => true,
               'allow_add'     => true,
               'allow_delete'   => true,
               'required' =>false,
               'label' => false,
               
            ])

            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,

        ]);
    }
}
