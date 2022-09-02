<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Entity\Comment;
use App\Form\TrickType;
use App\Form\CommentType;
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
use Symfony\Component\HttpFoundation\JsonResponse;
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

        // WORKS WITH THE LOADMORE BUTTON
       $tricks = $repo->findBy([], ['createdAt' => 'DESC']);
        
		// get the total number of tricks
		$totalTricks = count($tricks);

        return $this->render('tricks/index.html.twig', [
            'controller_name' => 'TricksController',
            'tricks' => $tricks
        ]);

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

        $trick = new Trick();
        $dbDefaultimage = "";
  
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

            return $this->redirectToRoute('app_tricks', ['_fragment' => 'tricks']);
        }

          return $this->render('tricks/create.html.twig', [
            'formTrick' => $form->createView(),
            'trick' => $trick,

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

        if(!$trick){
            $trick = new Trick();
            $dbDefaultimage = "";
        }else{
            //SAVE THE NAME OF Trick DEFAULT IMAGE and Trick Title
            $dbDefaultimage = $trick->getDefaultimage();
            $dbTitle = $trick->getTitle();
        }

        // DB Saved trick videos collection
        $originalVideos = $this->videoService->savedVideos($trick);
        // DB Saved trick images collection
        $originalImages = $this->imageService->savedImages($trick);

        $form = $this->createForm(TrickType::class, $trick);
 
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            if( ($this->getUser( ) != $trick->getUser()) && ($dbTitle != $form->get('title')->getData())){
                $this->addFlash('danger', 'Vous ne pouvez pas modifier le nom de la figure !');
           
                return $this->render('tricks/update.html.twig', [
                    
                     'formUpdateTrick' => $form->createView(),
                     'trick' => $trick,
                     
                 ]); 
            }
            
            //Remove videos from $originalVideos
            $this->videoService->checkSavedVideos($trick, $originalVideos);

            //Remove images from $originalImages
            $this->imageService->checkSavedImages($form['images']->getData(), $originalImages);//ADDED FOR IMAGES DELETION TESTS 

            //HANDELING NEW IMAGES AND VIDEOS
            $this->imageService->manageImages($trick, $form, $dbDefaultimage);
            $this->videoService->manageVideos($trick, $form);

            $trick->setUpdatedAt(new \DateTime());
            $manager->flush();

            $this->addFlash('success', 'La Figure a été mise à jour avec succès !');

            return $this->redirectToRoute('app_tricks', ['_fragment' => 'tricks']);
        }
       
          return $this->render('tricks/update.html.twig', [

            'formUpdateTrick' => $form->createView(),
            'trick' => $trick,

        ]); 
    }

    /**
     * @Route("/tricks/figure/{slug}-{id}", name="trick_show", requirements = {"slug": "[a-z0-9\-]*"})
     */
    
    public function show( Trick $trick, CommentRepository $commentRepository,  Request $request, EntityManagerInterface  $manager, string $slug ): Response  // ParamConverter : conversion parametre en objet
    {
        if($this->trickService->urlSlug($trick->getTitle()) !== $slug){
            return $this->redirectToRoute('trick_show', [
                'id' => $trick->getId(),
                'slug' => $this->trickService->create_url_slug($trick->getTitle()),
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

            return $this->redirectToRoute('trick_show', [
                'id' => $trick->getId(),
                'slug' => $this->trickService->urlSlug($trick->getTitle()),
                
            ], 301);
        }
        $limit = 10;
        
        $page = (int)$request->query->get("page",1);

        // WORKS WITH THE  PAGINATION
        $comments = $commentRepository->getComments($trick->getId(), $page, $limit);
        
		$totalComments = count($comments);
        
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
     * @Route("/suppression/image/{id}", name="trick_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Request $request, Image $image, ImageRepository $imageRepository): Response
    {
        if(isset($request->request))
        {
            $data = json_decode($request->getContent(), true);

        $submittedToken = $request->request->get('_token');
        
        }
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $submittedToken)) {
            $name = $image->getName();
            unlink($this->getParameter('images_directory').'/'.$name);

            $imageRepository->remove($image, true);// DELETE FROM DB
            
            return $this->render('tricks/update.html.twig', [
                'formUpdateTrick' => $form->createView(),
                'trick' => $image->getTrick(),
                
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
        json_decode($request->getContent(), true);
       
        $submittedToken = $request->request->get('_token');
 
        if ($this->isCsrfTokenValid('delete'.$video->getId(), $submittedToken)) {
           
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
    {
        if ($this->isCsrfTokenValid('delete_trick_'.$trick->getId(), $request->get('_token'))) {
            
            $this->trickService->manageDeletion($trick);

                $this->addFlash('success', 'La figure a été supprimé avec succès !');

                return $this->redirectToRoute('app_tricks', ['_fragment' => 'tricks']);

        }
        $this->addFlash('error', 'Erreur lors de l\'opration !');

        return $this->redirectToRoute('app_tricks');
    }
    
}
