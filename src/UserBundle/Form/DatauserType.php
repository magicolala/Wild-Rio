<?php

// src/AppBundle/Form/ProductType.php
namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DatauserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userNom', TextType::class, array('label' => 'Nom'))
            ->add('userPrenom', TextType::class, array('label' => 'Prenom'))
            ->add('userVille', TextType::class, array('label' => 'Ville'))
            ->add('userFonction', TextType::class, array('label' => 'Fonction'))
            ->add('userImage', FileType::class, array('label' => 'Image (JPEG file)', 'data_class' => null ))
            ->add('save', SubmitType::class, array('label' => 'Valider les informations'))
            ->getForm();
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\Datauser',
        ));
    }
}

?>