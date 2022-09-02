<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{


    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request){


            // catch login error 
            $error = $authenticationUtils->getLastAuthenticationError();
            // catch last username 
            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render('security/login.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error,
                '_fragment' => 'login',
                'back_to_your_page' => $request->headers->get('referer')
               
            ]);
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout(){}

}


