<?php

namespace App\Service;

use App\Entity\Image;
use App\Entity\Trick;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }
   
    /**
     *  trick's images adding.
     * @return void
     */
    public function manageImages(Trick $trick, FormInterface $form, string $dbDefaultimage)
    {
        // GET DEFAULT IMAGE
        //dd($dbDefaultimage);
        if($form->get('defaultimage')->getData() !== null){

            $defaultImage = $form->get('defaultimage')->getData();
            $fichier = md5(uniqid()) . '.' .$defaultImage->guessExtension();
            $defaultImage->move(
                $this->params->get('images_directory'), 
                    $fichier
                );
        }else{
            $fichier = $dbDefaultimage;
        }
        
        //if (null !== $fichier) {
            //$fichier = md5(uniqid()) . '.' .$defaultImage->guessExtension();
           

            $trick->setDefaultimage($fichier);
        //}

        //  IMAGES UPLOADS
        $images = $form->get('images')->getData();

        if (null !== $images) {
            foreach ( $images as $image ) {
                $fichier = md5(uniqid()) . '.' .$image->guessExtension();

                $image->move(
                    $this->params->get('images_directory'), 
                    $fichier
                );

                //  IMAGES INSERTION IN DB 
                $img = new Image();
                $img->setName($fichier);
                
                $trick->addImage($img);
            }
        }  
    }

     /**
     * @Route("/suppression/image/{id}", name="trick_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Request $request, Images $image, ImagesRepository $imagesRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])) {
            $name = $image->getName();
            unlink($this->getParameter('images_directory').'/'.$name);

            $imagesRepository->remove($image, true);// DELETE FROM DB

            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }

    }   

}
