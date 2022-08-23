<?php

namespace App\Service;

use App\Entity\Image;
use App\Entity\Trick;
use App\Helper\Uploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageService
{
    private $params;

    public function __construct(ParameterBagInterface $params, Uploader $uploader, EntityManagerInterface $entityManager)
    {
        $this->params = $params;
        $this->uploader = $uploader;
        $this->entityManager = $entityManager;
    }
   
    /**
     *  trick's images adding.
     * @return void
     */
    public function manageImages(Trick $trick, FormInterface $form, string $dbDefaultimage)
    {
        // GET DEFAULT IMAGE
        
        if($form->get('defaultimage')->getData() !== null){

            $defaultImage = $form->get('defaultimage')->getData();
            $fichier = $this->uploader->uploadFile($defaultImage);
            
        }else{
            $fichier = $dbDefaultimage;
        }
        
        $trick->setDefaultimage($fichier);
      
        //  IMAGES UPLOADS
        $images = $form->get('images')->getData();
        
        if (null !== $images) {
            foreach ( $images as $image ) {
                //dd($image);
                if(null !== $image->getFile()){
                    $fichier = $this->uploader->uploadFile($image->getFile());
               
                    //  IMAGES INSERTION IN DB 

                    $image->setName($fichier);
                    
                    $trick->addImage($image);
                }
            }
        }  
    }

    /**
     *  save trick's existing videos .
     * @return array $originalVideos
     */
    public function savedImages(Trick $trick)
    {
        $originalImages = new ArrayCollection();

        // Create an ArrayCollection of the current video objects in the database
        foreach ($trick->getImages() as $image) {
            $originalImages->add($image);
        }
        return $originalImages;
    }


    /**
     *  delete saved trick's existing images .
     * @return void
     */
    
    public function checkSavedImages( $formImages,  $originalImages)
    {
        // remove the relationship between the tag and the Task
        foreach ($originalImages as $originalImage) {
            
            if (false === $formImages->contains($originalImage)) {
               
                // if it was a many-to-one relationship, remove the relationship like this
                $originalImage->setTrick(null);

                $this->entityManager->persist($originalImage);

            }
        }
    }

}
