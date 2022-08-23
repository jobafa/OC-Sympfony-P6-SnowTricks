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

class UserController extends AbstractController{

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var MailSender
     */
    private $mailSender;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserService $userService, MailSender $mailSender, UserRepository $userRepository)
    {
        $this->userService = $userService;
        $this->mailSender = $mailSender;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/inscription", name="user_registration")
     */

    public function registration(Request $request) {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){

            $token = $this->userService->registerUser($user, $form);
//
            $url = $this->generateUrl('user_activation', ['token' => $token]);
            $isEmailSent = $this->mailSender->sendEmail( $user, ['url' => $url], 'activation');

            $this->addFlash('success', "Votre compte a été créé avec succès. Pour l'activer, merci de cliquer sur le lien envoyé sur votre email !");
            
            return $this->redirectToRoute('security_login');

        }

        return $this->render('user/registration.html.twig', [
            'formRegister' => $form->createView()
        ]);
    }

    /**
     * @Route("/activation/{token}", name="user_activation")
     */
    public function activation($token, UserRepository $user)
    {
        // check if user exists
        $user = $user->findOneBy(['activationToken' => $token]);

        // if no user : error message
        if(!$user){
            $this->addFlash('danger', "Activation impossible Cet utilisateur n\'existe pas");
            return $this->redirectToRoute('security_login');
        }

        // delete token
        $this->userService->userActivation($user);
        // message generate 
        $this->addFlash('success', 'Votre compte a été activé avec succès');

        // redirect to login form
        return $this->redirectToRoute('security_login');
       // return $this->redirectToRoute('app_tricks');
    }

     /**
     * gets user's email, generates passreset token and emails link to user.
     *
     * @Route("/demande_reinitialisation", name="user_forgotpassword_request")
     *
     * @return Response
     */
    public function newPasswordRequest(Request $request)
    {
        $form = $this->createForm(PassResetRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $formUserName = $form->getData();
            //dd($formUserName);
            $user = $this->userRepository->findOneByUsername($formUserName['username']);

            if (!$user) {
                $this->addFlash('danger', 'Le Nom d\'utilisateur n\'existe pas !');

                return $this->redirectToRoute('user_forgotpassword_request');
            }
            
            $token = $this->userService->passwordResetToken($user);

            $url = $this->generateUrl('user_password_reset', ['token' => $token]);

            $isEmailSent = $this->mailSender->sendEmail($user, ['url' => $url], 'reinitialisation');

            $this->addFlash('success', 'Afin de réintialiser votre mot de passe, merci de cliquer sur le lien envoyé sur votre email !');

            return $this->redirectToRoute('user_forgotpassword_request');
        }

        return $this->render('user/forgot_password.html.twig', [
            'formpassrequest' => $form->createView(),
        ]);
    }

    /**
     * verify link token and displays new password form.
     *
     * @Route("/reinitialisation/{token}", name="user_password_reset")
     *
     * @param string $token
     *
     * @return Response
     */
    public function newPassword($token, Request $request)
    {
        $user = $this->userRepository->findOneBy(['passresetToken' => $token]);

        if (!$user) {
            $this->addFlash('danger', 'le token est invalide !');

            return $this->redirectToRoute('user_forgotpassword_request');
        }

        $form = $this->createForm(NewPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->newPassword($user, $form->get('password')->getData());
            $this->addFlash('success', 'Votre nouveau mot de passe est enregisré');

            return $this->redirectToRoute('security_login');
        }

        return $this->render('user/new_password.html.twig', [
            'user' => $user,
            'formnewpass' => $form->createView(),
        ]);
    }
}