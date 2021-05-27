<?php

namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description')
            //champ filetype pour que le navigateur peut afficher le widget de telechargement de l'image  et creer dans service.yaml image_directory ->
            //image_directory: '%kernel.project_dir%/public/uploads' dans lequel les images doivent etre stockées et ajouter un dossier uploads sous public
            ->add('image',filetype::class, array('data_class' => null,'required' => false)
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
