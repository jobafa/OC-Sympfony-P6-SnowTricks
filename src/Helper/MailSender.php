<?php

namespace App\Helper;

use App\Entity\User;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailSender extends AbstractController
{
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        
    }

    #[Route('/email')]
    public function sendEmail(User $user, array $link, string $linkPurpose)
    {
        $email = (new TemplatedEmail())
            //->from('Site SnowTricks <contact@capdeco.com>')
            ->to($user->getEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            //->subject('Activation compte')
            //->text('Sending emails is fun again!')
            ->context($link)
            ->htmlTemplate('email/'.$linkPurpose.'.html.twig');

            if($linkPurpose == 'activation'){
                $email->subject('Activation compte'); 
            }elseif($linkPurpose === 'Reinitialisation'){
                $email->subject('Réinitialisation mot de passe'); 
            }
            try {
                return $this->mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                throw $e;
                // some error prevented the email sending; display an
                // error message or try to resend the message
            }
            //return $this->mailer->send($email);

        // ...
    }
}