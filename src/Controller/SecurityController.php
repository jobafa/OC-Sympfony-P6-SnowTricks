<?php

namespace App\Controller;

use App\Entity\User;
use App\Helper\MailSender;
use App\Service\UserService;
use App\Form\NewPasswordType;
use App\Form\RegistrationType;
use App\Form\PassResetRequestType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
      
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var MailSender
     */
    private $mailSender;

   /*  public function __construct(UserService $userService, MailSender $mailSender, UserRepository $userRepository)
    {
        $this->userService = $userService;
        $this->mailSender = $mailSender;
        $this->userRepository = $userRepository;
    }
 */
    /**
     * @Route("/inscription", name="security_registration")
     */

   /*  public function registration(Request $request) {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){

            $token = $this->userService->registerUser($user, $form);
//
            $url = $this->generateUrl('security_activation', ['token' => $token]);
            $isEmailSent = $this->mailSender->sendEmail( $user, ['url' => $url], 'activation');

            $this->addFlash('success', "Votre compte a été créé avec succès. Pour l'activer, merci de cliquer sur le lien envoyé sur votre email !");
            

            return $this->redirectToRoute('security_login');

        }

        return $this->render('security/registration.html.twig', [
            'formRegister' => $form->createView()
        ]);
    } */

    /**
     * @Route("/activation/{token}", name="security_activation")
     */
   /*  public function activation($token, UserRepository $user)
    {
        // check if user exists
        $user = $user->findOneBy(['activationToken' => $token]);

        // if no user : error message
        if(!$user){
            $this->addFlash('danger', "Activation impossible Cet utilisateur n\'existe pas");
           // throw $this->createNotFoundException("Activation impossible Cet utilisateur n\'existe pas");
        }

        // delete token
        $this->userService->userActivation($user);
        // message generate 
        $this->addFlash('success', 'Utilisateur activé avec succès');

        // redirect to login form
        return $this->redirectToRoute('security_login');
    } */

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request){

        //$backToPrevious = $request->form->get('_target_path')->getData();
        // if ($this->getUser()) {
        //          return $this->redirectToRoute('target_path_parameter');
        // }dd('target_path_parameter');

        //if($authenticationUtils->getLastAuthenticationError()){

            // catch login error 
            $error = $authenticationUtils->getLastAuthenticationError();
            // catch last username 
            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render('security/login.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error,
                '_fragment' => 'login',
                'back_to_your_page' => $request->headers->get('referer')
               // 'back_to_your_page' => 'http://127.0.0.1:8000/tricks/new'
               
            ]);
        //}
        
        //return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout(){}


    /**
     * gets user's email, generates passreset token and emails link to user.
     *
     * @Route("/demande_reinitialisation", name="seurity_forgotpassword_request")
     *
     * @return Response
     */
    /* public function newPasswordRequest(Request $request)
    {
        $form = $this->createForm(PassResetRequestType::class);
        $form->handleRequest($request);
//dd($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $formemail = $form->getData();
            $user = $this->userRepository->findOneByEmail($formemail['email']);
//dd($formemail);
            if (!$user) {
                $this->addFlash('danger', 'L\'email n\'existe pas !');

                return $this->redirectToRoute('seurity_forgotpassword_request');
            }
            
      

            $token = $this->userService->passwordResetToken($user);

            $url = $this->generateUrl('security_password_reset', ['token' => $token]);

            $isEmailSent = $this->mailSender->sendEmail($user, ['url' => $url], 'reinitialisation');

            $this->addFlash('success', 'Afin de réintialiser votre mot de passe, merci de cliquer sur le lien envoyé sur votre email !');

            return $this->redirectToRoute('seurity_forgotpassword_request');
        }

        return $this->render('security/forgot_password.html.twig', [
            'formpassrequest' => $form->createView(),
        ]);
    }
 */
    /**
     * verify link token and displays new password form.
     *
     * @Route("/reinitialisation/{token}", name="security_password_reset")
     *
     * @param string $token
     *
     * @return Response
     */
    /* public function newPassword($token, Request $request)
    {
        $user = $this->userRepository->findOneBy(['passresetToken' => $token]);

        if (!$user) {
            $this->addFlash('danger', 'le token est invalide !');

            return $this->redirectToRoute('seurity_forgotpassword_request');
        }

        $form = $this->createForm(NewPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->newPassword($user, $form->get('password')->getData());
            $this->addFlash('success', 'Votre nouveau mot de passe est enregisré');

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/new_password.html.twig', [
            'user' => $user,
            'formnewpass' => $form->createView(),
        ]);
    }
 */
    /**
     * @Route("/security", name="app_security")
     */
   /* public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }*/
}
