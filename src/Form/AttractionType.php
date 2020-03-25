<?php

namespace App\Form;

use App\Entity\Attraction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttractionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null ,array('label' => 'Titre'))
            ->add('description')
            ->add('age', null ,array('label' => 'Âge'))
            ->add('size', null ,array('label' => 'Taille'))
            ->add('season', null ,array('label' => 'Saison'))
            ->add('imageFile', FileType::class, [
                'required' => false,
                'label' => 'Image'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Attraction::class,
        ]);
    }
}
