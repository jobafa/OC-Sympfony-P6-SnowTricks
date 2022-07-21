<?php

namespace App\Service;

use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
//use Symfony\Component\Form\FormInterface;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
//use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;

class TrickService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ContainerBag
     */
    /* private $params; */
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        //$this->params = $params;
        
    }

    
    /**
     * Handle trick deletion in database.
     *
     * @return void
     */
    public function manageDeletion(Trick $trick)
    {//dd($trick);
        try {
            /* $this->imageService->handleImageFolderDeletion($trick); */
            //unlink($this->params->getParameter('images_directory').'/'.$name);
            $this->entityManager->remove($trick);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    
}
