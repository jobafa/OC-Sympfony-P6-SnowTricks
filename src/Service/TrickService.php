<?php

namespace App\Service;

use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;


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
    {
        try {
            
            $this->entityManager->remove($trick);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Handle url sluggifying.
     *
     * @return string
     */
    public function urlSlug($string){

        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);

        return strToLower($slug);

     }

    
}
