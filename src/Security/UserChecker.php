<?php
namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        /* if ($user->isDeleted()) {
           // the message passed to this exception is meant to be displayed to the user
             throw new CustomUserMessageAccountStatusException("Votre compte n'existe plus !.");
        } */
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if (null !== $user->getActivationToken()) {
            throw new \Exception('Votre compte n\'est pas activé. consultez votre boite mail pour l\'activer !');

           // $this->addFlash('danger', 'Votre compte n\'est pas activé. consultez votre boite mail pour l\'activer !');

            $this->render('security/login.html.twig');
        }

        // user account is expired, the user may be notified
        //if ($user->isExpired()) {
            //throw new AccountExpiredException('...');
        //}
    }
}