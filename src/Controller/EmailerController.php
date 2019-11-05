<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailerController extends AbstractController
{
    /**
     * @Route("/emailer", name="emailer")
     */
    public function index()
    {
        return $this->render('emailer/index.html.twig', [
            'controller_name' => 'EmailerController',
        ]);
    }

    /**
     * @Route("/email")
     */
    public function sendEmail(\Swift_Mailer $mailer)
    {
        $name = "john";
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('morteza_faraji@email.com')
            ->setTo('morteza_faraji@yahoo.com')
            ->setBody(
                $this->renderView(
                    // templates/emails/registration.html.twig
                    'emailer/registration.html.twig',
                    ['name' => $name]
                ),
                'text/html'
            );

        $mailer->send($message);

        return $this->render('emailer/index.html.twig', [
            'controller_name' => 'EmailerController',
        ]);
    }    
}
