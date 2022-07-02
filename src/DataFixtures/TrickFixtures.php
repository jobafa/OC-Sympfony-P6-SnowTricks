<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Trick;

class TrickFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i <= 10; $i++){
            $trick = new Trick();
            $trick->setTitle("Titre de la figire n° $i");
            $trick->setContent("<p>contenu de la figire n° $i</p>");
            $trick->setImage("https://via.placeholder.com/350x150");
            $trick->setCreatedAt(new \DateTime());
            $trick->setUpdatedAt(new \DateTime());
            $manager->persist($trick);
        }
        
        $manager->flush();
    }
}
