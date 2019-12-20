<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Symfony\Component\HttpFoundation\Request;

use Psr\Log\LoggerInterface;

class UserController extends AbstractController
{
    private $userReposity;
    private $passwordEncoder;

    private $logger;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $userRepository, LoggerInterface $logger)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;

        $this->logger = $logger;
    }

    /**
     * @Route("/signup", name="signup")
     */
    public function signup(EntityManagerInterface $em, Request $request, \Swift_Mailer $mailer)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user = $form->getData();
            $user->setRoles(array("ROLE_USER"));
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($user);
            // $entityManager->flush();

            $message = (new \Swift_Message('Premium Millionaire - You just signed up!'))
                ->setFrom('morteza_faraji@email.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'user/emails/signed-up.html.twig',
                        [
                            // 'tempTicketId' => $tempTicketId,
                            // 'bitcoinWallet' => $bitcoinWallet,
                        ]
                    ),
                    'text/html'
                );

    
            $mailer->send($message);
    
            return $this->redirectToRoute('dashboard');
        }
    
        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);            
    }

    /**
     * @Route("/reset", name="reset")
     */
    public function reset(EntityManagerInterface $em, Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user = $form->getData();
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
    
            return $this->redirectToRoute('dashboard');
        }
    
        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);            
    }    
}
