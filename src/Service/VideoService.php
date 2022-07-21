<?php

namespace App\Service;

use App\Entity\Video;
use App\Entity\Trick;
//use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;


class VideoService
{
    /**
     * @var EntityManagerInterface
     */
    //private $entityManager;

  
    /* public function __construct(EntityManagerInterface $entityManager)
    {
        //$this->entityManager = $entityManager;
    } */


/**
     *  trick's videos adding.
     * @return void
     */
    public function manageVideos(Trick $trick, FormInterface $form)
    {
        $videos = $form->get('videos')->getData();
            foreach ( $videos as $video ) {
                
                // INSERTION DB VIDEOS ENTITY
                $videoUrl = $video->getVideourl();
                $video->setVideourl($videoUrl);
                $trick->addVideo($video);
            }

      
    }
}