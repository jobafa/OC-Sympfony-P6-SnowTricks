<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class UserService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager, TokenGeneratorInterface $tokenGenerator)
    {
        $this->encoder = $encoder;
        $this->entityManager = $entityManager;
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     *  user registration.
     * @return String $token
     */
    public function registerUser(User $user, FormInterface $form)
    {
       
            $hash = $this->encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);
            $user->setCreatedAt(new \DateTime());
            $user->setUpdatedAt(new \DateTime());
            
            $token = $this->tokenGenerator->generateToken();
            $user->setActivationToken($token);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $token;
        
    }

   
    /**
     *  user activation .
     * @return void
     */
    public function userActivation(User $user)
    {
            $user->setActivationToken(null);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
    }

    /**
     * generates user password reset token and store in Db
     * @return String $token
     */
    public function passwordResetToken(User $user)
    {
        try {
            $token = $this->tokenGenerator->generateToken();
            $user->setPassresetToken($token);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $token;
        } catch (\Exception $e) {
            $this->addFlash('warning', $e->getMessage());
            return $this->redirectToRoute('security_login');
        }
    }

    /**
     * password reset.
     *
     * @return void
     */
    public function newPassword(User $user, string $password)
    {
        try {
            $user->setPassword(
            $this->encoder->encodePassword($user, $password)
            );
            $user->setPassresetToken(null);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}
