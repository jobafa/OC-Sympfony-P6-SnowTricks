<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('file', FileType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => "Charger l'image",
            ],
        ])
          ->add('delete', ButtonType::class, [
            'label_html' => true,
            'label' => "<i class='fas fa-times-circle fa-stack-2x'></i>",
            'attr' => [
                'data-action' => 'delete',
                'data-target' => '#trick_images___name__',
            ],
        ]);
           
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
