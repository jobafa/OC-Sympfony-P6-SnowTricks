<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Entity\Comment;
use App\Form\TrickType;
//use Doctrine\Common\Persistence\ObjectManager;
use App\Form\CommentType;
use App\Form\OthersTrickType;
use App\Service\ImageService;
use App\Service\TrickService;
use App\Service\VideoService;
use App\Repository\ImageRepository;
use App\Repository\TrickRepository;
use App\Repository\VideoRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TricksController extends AbstractController
{
    
    /**
     * @var ImageService
     */

    private $imageService;

    /**
     * @var TrickService
     */

    private $trickService;



    public function __construct(ImageService $imageService, VideoService $videoService, TrickService $trickService)
    {
        $this->videoService = $videoService;
        $this->imageService = $imageService;
        $this->trickService = $trickService;
    
    }

   
    /**
     * @Route("/tricks/" , name="app_tricks")
     * @Route("/", name="home")
     */
    public function index(TrickRepository $repo, Request $request): Response
    {
        $limit = 5;
        $page = (int)$request->query->get("page",1);

        // WORKS WITH THE  PAGINATION
        //$tricks = $repo->getTricks($page, $limit);// MODIF DU 14 07

        // WORKS WITH THE LOADMORE BUTTON
       $tricks = $repo->findBy([], ['createdAt' => 'DESC']);
        
		// get the total number of tricks
		$totalTricks = count($tricks);

        //$tricks = $repo->findAll();
        return $this->render('tricks/index.html.twig', [
            'controller_name' => 'TricksController',
            'tricks' => $tricks
        ]);

        /* return $this->render('tricks/index.html.twig', [
            'controller_name' => 'TricksController',
            'tricks' => $tricks,
            'totaltricks' => $totalTricks,
            'limit' => $limit,
            'page' => $page
        ]); */

    }
    
    /**
    * @Route("/tricks/new", name = "trick_create")
    */

    public function createTricks(Trick $trick = null,  Request $request, EntityManagerInterface  $manager): Response
    {  
        if(!$this->getUser()){
            $this->addFlash('danger', 'Vous devez vous connecter pour effectuer cette tache !');

            // redirect to login form
            return $this->redirectToRoute('security_login', ['_fragment' => 'login']);
        }
        if(!$trick){
            $trick = new Trick();
            $dbDefaultimage = "";
        }
        /* else{
            //SAVE YHE NAME OF DEFAULT IMAGE 
            $dbDefaultimage = $trick->getDefaultimage();
        } */
        
        $form = $this->createForm(TrickType::class, $trick);
               
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){

            $this->imageService->manageImages($trick, $form, $dbDefaultimage);
            $this->videoService->manageVideos($trick, $form);

            if(!$trick->getId()){
                $trick->setCreatedAt(new \DateTime());
                
            }
            $trick->setUpdatedAt(new \DateTime());

            $trick->setUser($this->getUser());
            
            $manager->persist($trick);
            $manager->flush();

            $this->addFlash('success', 'La Figure a été ajoutée avec succès !');

            //return $this->redirectToRoute('trick_show', ['id' => $trick->getId()]);
            return $this->redirectToRoute('app_tricks', ['_fragment' => 'tricks']);
        }

        /* else{
            $request->getSession()->set('sessionimage', $dbDefaultimage);
        } */
        //dd($trick);
      /* return $this->render('tricks/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);*/

          return $this->render('tricks/create.html.twig', [
            'formTrick' => $form->createView(),
            'trick' => $trick,
            //'editMode' => $trick->getId() !== null
        ]); 
    }

    /**
    * @Route("/tricks/{id}/edit", name = "trick_edit")
    */

    public function updateTrick(Trick $trick = null,  Request $request, EntityManagerInterface  $manager): Response
    {  
        if(!$this->getUser()){
            $this->addFlash('danger', 'Vous devez vous connecter pour effectuer cette tache !');

            // redirect to login form
            return $this->redirectToRoute('security_login', ['_fragment' => 'login']);
        }

        // Saved trick videos collection
        $originalVideos = $this->videoService->savedVideos($trick);

        // Saved trick images collection
        $originalImages = $this->imageService->savedImages($trick);
        /* $originalVideos = new ArrayCollection();

        // Create an ArrayCollection of the current video objects in the database
        foreach ($trick->getVideos() as $video) {
            $originalVideos->add($video);
        } */

        if(!$trick){
            $trick = new Trick();
            $dbDefaultimage = "";
        }else{
            //SAVE YHE NAME OF DEFAULT IMAGE 
            $dbDefaultimage = $trick->getDefaultimage();
        }

        if($this->getUser() == $trick->getUser()){
            $form = $this->createForm(TrickType::class, $trick);
        }else{
            $form = $this->createForm(OthersTrickType::class, $trick);
        }

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){

            //Remove videos from $originalVideos
            $this->videoService->checkSavedVideos($trick, $originalVideos);

            //Remove images from $originalImages
            $this->imageService->checkSavedImages($trick, $originalImages);

            /* // remove the relationship between the tag and the Task
            foreach ($originalVideos as $originalVideo) {
                if (false === $trick->getVideos()->contains($originalVideo)) {
                    // remove the Task from the Tag
                   // $originalVideo->getId()->removeElement($trick);

                    // if it was a many-to-one relationship, remove the relationship like this
                     $originalVideo->setTrick(null);

                    $manager->persist($originalVideo);

                    // if you wanted to delete the Tag entirely, you can also do that
                    // $entityManager->remove($tag);
                }
            } */
         
            $this->imageService->manageImages($trick, $form, $dbDefaultimage);
            $this->videoService->manageVideos($trick, $form);

            /* if(!$trick->getId()){
                $trick->setCreatedAt(new \DateTime());
                
            } */
            $trick->setUpdatedAt(new \DateTime());

            // $trick->setUser($this->getUser());
            
            // $manager->persist($trick);
            $manager->flush();

            $this->addFlash('success', 'La Figure a été mise à jour avec succès !');

            //return $this->redirectToRoute('trick_show', ['id' => $trick->getId()]);
            return $this->redirectToRoute('app_tricks', ['_fragment' => 'tricks']);
        }/* else{
            $request->getSession()->set('sessionimage', $dbDefaultimage);
        } */
        //dd($trick);
      /* return $this->render('tricks/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            
            
        ]);*/
          return $this->render('tricks/update.html.twig', [
           // return $this->render('tricks/edit.html.twig', [
            'formUpdateTrick' => $form->createView(),
            'trick' => $trick,
            //'editMode' => $trick->getId() !== null
        ]); 
    }

    /**
     * @Route("/tricks/figure/{slug}-{id}", name="trick_show", requirements = {"slug": "[a-z0-9\-]*"})
     */
    
    public function show( Trick $trick, CommentRepository $commentRepository,  Request $request, EntityManagerInterface  $manager, string $slug ): Response  // ParamConverter : conversion parametre en objet
    {
        
        if($trick->getSlug() !== $slug){
            return $this->redirectToRoute('trick_show', [
                'id' => $trick->getId(),
                'slug' => $trick->getSlug(),
            ], 301);
        }
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            $comment->setCreatedAt(new \DateTime())
                    ->setAuthor($this->getUser()->getUsername())
                    ->setUsers($this->getUser())
                    ->setTrick($trick);

            $manager->persist($comment);
            $manager->flush();

            //return $this->redirectToRoute('trick_show', ['id' => $trick->getId()]);
            return $this->redirectToRoute('trick_show', [
                'id' => $trick->getId(),
                'slug' => $trick->getSlug(),
            ], 301);
        }
        $limit = 4;
        //$paginatedResult = $repo->getTricks(1);
        $page = (int)$request->query->get("page",1);

        // WORKS WITH THE  PAGINATION
        $comments = $commentRepository->getComments($trick->getId(), $page, $limit);
        //$comments = $commentRepository->findBy(['Trick' => '$trick->getId()'], ['createdAt' => 'DESC'], $page, $limit);
        //dd($comments);
        // get the total number of comments
		$totalComments = count($comments);
        //dd($totalComments);
        return $this->render('tricks/show.html.twig', [
            'trick' => $trick,
            'commentForm' => $form->createView(),
            'comments' => $comments,
            'totalComments' => $totalComments,
            'limit' => $limit,
            'page' => $page
        ]);
    }

     /**
     * @Route("/suppression/imagePrincipale/{id}", name="trick_defaultImage_delete", methods={"GET", "POST", "DELETE"})
     */
    public function deleteDefaultImage(Trick $trick, Request $request, EntityManagerInterface  $manager): Response
    {
        $submittedToken = $request->request->get('_token');// does not work ???????????

        if($this->getUser() == $trick->getUser()){
            $form = $this->createForm(TrickType::class, $trick);
        }else{
            $form = $this->createForm(OthersTrickType::class, $trick);
        }
        $form->handleRequest($request);
 //dd($trick->getDefaultimage());
// dd($request);
        //if ($this->isCsrfTokenValid('delete_'.$defaultImage, $submittedToken)) {
            //$name = $image->getName();
            //$defaultImage = $form->get('defaultimage')->getData();
            $defaultImage = $trick->getDefaultimage();
            if($defaultImage !== ""){
                $trick->setDefaultimage("");
                $manager->persist($trick);
                $manager->flush();
                
                unlink($this->getParameter('images_directory').'/'.$defaultImage);
            }
            //$trickRepository->remove($image, true);// DELETE FROM DB

            //return new JsonResponse(['success' => 1]);
            return $this->render('tricks/update.html.twig', [
                'formUpdateTrick' => $form->createView(),
                'trick' => $trick,
                //'editMode' => $trick->getId() !== null
            ]); 
        /* }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        } */

    }   

    /**
     * @Route("/suppression/image/{id}", name="trick_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Request $request, Image $image, ImageRepository $imageRepository): Response
    {//dd(json_decode($request->getContent(), true));
        if(isset($request->request))
        {
            $data = json_decode($request->getContent(), true);
            //dd($data);
        
        $submittedToken = $request->request->get('_token');
        dd($request);
        //dd($image->getTrick());
        }
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $submittedToken)) {
            $name = $image->getName();
            unlink($this->getParameter('images_directory').'/'.$name);

            $imageRepository->remove($image, true);// DELETE FROM DB

            //return new JsonResponse(['success' => 1]);
            return $this->render('tricks/update.html.twig', [
                'formUpdateTrick' => $form->createView(),
                'trick' => $image->getTrick(),
                //'editMode' => $trick->getId() !== null
            ]); 
        }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }

    }   

    /**
     * @Route("/suppression/video/{id}", name="trick_delete_video", methods={"GET", "POST", "DELETE"})
     */
    public function deleteVideo(Request $request, Video $video, VideoRepository $videoRepository): Response
    {
        $data = json_decode($request->getContent(), true);
       
        $submittedToken = $request->request->get('_token');
 //dd($submittedToken);
        if ($this->isCsrfTokenValid('delete'.$video->getId(), $submittedToken)) {
            /* $name = $image->getName();
            unlink($this->getParameter('images_directory').'/'.$name); */

            $videoRepository->remove($video, true);// DELETE FROM DB

            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }

    }   

     /**
     * Handle Trick deletion.
     *
     * @Route("/suppression/figure/{id}", name="trick_delete", methods={"POST", "DELETE"})
     *
     * @return Response
     */
    public function delete(Request $request, Trick $trick)
    {//dd($trick);
        if ($this->isCsrfTokenValid('delete_trick_'.$trick->getId(), $request->get('_token'))) {
            
            $this->trickService->manageDeletion($trick);

            //if ($trick->getUser() === $this->getUser()) {
                $this->addFlash('success', 'La figure a été supprimé avec succès !');

                return $this->redirectToRoute('app_tricks', ['_fragment' => 'tricks']);
           // }
           
        }
        $this->addFlash('error', 'Erreur lors de l\'opration !');

        return $this->redirectToRoute('app_tricks');
    }
    
}
