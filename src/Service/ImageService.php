<?php

namespace App\Service;

use App\Entity\Image;
use App\Entity\Trick;
use App\Helper\Uploader;
use Symfony\Component\Form\FormInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageService
{
    private $params;

    public function __construct(ParameterBagInterface $params, Uploader $uploader)
    {
        $this->params = $params;
        $this->uploader = $uploader;
    }
   
    /**
     *  trick's images adding.
     * @return void
     */
    public function manageImages(Trick $trick, FormInterface $form, string $dbDefaultimage)
    {//dd($form->get('images')->getData());
        // GET DEFAULT IMAGE
        //dd($request);
        if($form->get('defaultimage')->getData() !== null){

            $defaultImage = $form->get('defaultimage')->getData();
            $fichier = $this->uploader->uploadFile($defaultImage);
            /* $fichier = $trick->getUser().'-'.md5(uniqid()) . '.' .$defaultImage->guessExtension();
            $defaultImage->move(
                $this->params->get('images_directory'), 
                    $fichier
                ); */
        }else{
            $fichier = $dbDefaultimage;
        }
        //dd($trick->getUser());
        //if (null !== $fichier) {
            //$fichier = md5(uniqid()) . '.' .$defaultImage->guessExtension();
           

            $trick->setDefaultimage($fichier);
        //}

        //  IMAGES UPLOADS
        $images = $form->get('images')->getData();
        //dd($images);
        if (null !== $images) {
            foreach ( $images as $image ) {
                //dd($images);
                $fichier = $this->uploader->uploadFile($image->getFile());
                /* $fichier = $trick->getId().'-'.md5(uniqid()) . '.' .$image->guessExtension();

                $image->move(
                    $this->params->get('images_directory'), 
                    $fichier
                ); */

                //  IMAGES INSERTION IN DB 
                $img = new Image();
                $img->setName($fichier);
                
                $trick->addImage($img);
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
    public function checkSavedImages(Trick $trick,  $originalImages)
    {
        // remove the relationship between the tag and the Task
        foreach ($originalImages as $originalImage) {
            if (false === $trick->getImages()->contains($originalImage)) {
                // remove the Task from the Tag
            // $originalVideo->getId()->removeElement($trick);

                // if it was a many-to-one relationship, remove the relationship like this
                $originalImage->setTrick(null);

                $this->entityManager->persist($originalImage);

                // if you wanted to delete the Tag entirely, you can also do that
                // $entityManager->remove($tag);
            }
        }
    }

}
