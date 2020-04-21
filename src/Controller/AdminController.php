<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/mail/test", name="admin")
     */
    public function testMail(\Swift_Mailer $mailer)
    {
        $app_email = $_ENV['APP_EMAIL'];
    
        $message = (new \Swift_Message('Test Mail'))
            ->setFrom($app_email)
            ->setTo('morteza_faraji@yahoo.com')
            ->setBody(
                $this->renderView(
                    'admin/emails/test.html.twig',
                    []
                ),
                'text/html'
            );

        echo $mailer->send($message);

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }    
}
