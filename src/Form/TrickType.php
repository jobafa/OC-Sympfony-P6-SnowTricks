<?php

namespace App\Form;

use App\Entity\Trick;
use App\Form\ImageType;
use App\Form\VideoType;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    { 
         
        $builder
            ->add('category',EntityType::class, [
                'label'=>'Catégorie',
                'class'=>Category::class,
                'choice_label'=> 'name'
            ])
            ->add('title', TextType::class, [
                'label' => 'Nom Figure',
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('defaultimage', FileType::class, [
                'label' => 'Image Principale',
                'attr' => [
                    'placeholder' => "Charger l'image  Principale",
                ] ,
                'required' => false,
                'data_class' => null,
            ])
            
            // AJOUT CHAMPS "images"
            
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,        
        ])
            // AJOUT CHAMPS "videos"
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoType::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
        ]);
            // ->add('image')
            // ->add('video');
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
