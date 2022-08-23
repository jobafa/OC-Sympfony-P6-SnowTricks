<?php

namespace App\Service;

use App\Entity\Trick;
use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Doctrine\Common\Collections\ArrayCollection;


class VideoService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

  
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    } 


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

    /**
     *  save trick's existing videos .
     * @return array $originalVideos
     */
     public function savedVideos(Trick $trick)
    {
        $originalVideos = new ArrayCollection();

        // Create an ArrayCollection of the current video objects in the database
        foreach ($trick->getVideos() as $video) {
            $originalVideos->add($video);
        }
        return $originalVideos;
    }

    /**
     *  delete saved trick's existing videos .
     * @return void
     */
    public function checkSavedVideos(Trick $trick,  $originalVideos)
    {
        // remove the relationship between the tag and the Task
        foreach ($originalVideos as $originalVideo) {
            if (false === $trick->getVideos()->contains($originalVideo)) {
               
                // if it was a many-to-one relationship, remove the relationship like this
                $originalVideo->setTrick(null);

                $this->entityManager->persist($originalVideo);

            }
        }
    }

}