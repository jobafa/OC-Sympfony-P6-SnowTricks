<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\TrickType;
use App\Form\CommentType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
//use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TricksController extends AbstractController
{
    /**
     * @Route("/tricks", name="app_tricks")
     */
    public function index(TrickRepository $repo): Response
    {
        //$repo = $this->getDoctrine()->getRepository(Trick::class);

        $tricks = $repo->findAll();

        return $this->render('tricks/index.html.twig', [
            'controller_name' => 'TricksController',
            'tricks' => $tricks
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('tricks/home.html.twig');
    }
    
    /**
     * @Route("/tricks/new", name = "trick_create")
     * @Route("/tricks/{id}/edit", name = "trick_edit")
     */

    //public function create(Request $request, ObjectManager $manager): Response
    public function formHandle(Trick $trick = null, Request $request, EntityManagerInterface  $manager): Response
    {
        if(!$trick){
            $trick = new Trick();
        }
        

       /* $form = $this->createFormBuilder($trick)

            ->add('title')
            ->add('content')
            ->add('image')
            ->getForm();*/
        $form = $this->createForm(TrickType::class, $trick);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){

            if(!$trick->getId()){
               $trick->setCreatedAt(new \DateTime());
               $trick->setUpdatedAt(new \DateTime());
            }
            $trick->setUpdatedAt(new \DateTime());
            
            $manager->persist($trick);
            $manager->flush();

            return $this->redirectToRoute('trick_show', ['id' => $trick->getId()]);
        }

        return $this->render('tricks/create.html.twig', [
            'formTrick' => $form->createView(),
            'editMode' => $trick->getId() !== null
        ]);
    }

    /**
     * @Route("/tricks/figure/{id}", name="trick_show")
     */
    //public function show(TrickRepository $repo, $id): Response

    public function show( Trick $trick, Request $request, EntityManagerInterface  $manager ): Response  // ParamConverter : conversion parametre en objet
    {
        //$repo = $this->getDoctrine()->getRepository(Trick::class);

        //$trick = $repo->find($id);// grace au ParamConverter
         $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            $comment->setCreatedAt(new \DateTime())
                    ->setTrick($trick);

            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('trick_show', ['id' => $trick->getId()]);
        }
       
        return $this->render('tricks/show.html.twig', [
            'trick' => $trick,
            'commentForm' => $form->createView(),
        ]);
    }
    
}
