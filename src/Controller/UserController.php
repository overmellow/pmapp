<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordResetType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Symfony\Component\HttpFoundation\Request;

use Psr\Log\LoggerInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;

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

            $user = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $user->setRoles(array("ROLE_USER"));
            $user->setStatus('unconfirmed');
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            $entityManager->persist($user);
            $entityManager->flush();

            $message = (new \Swift_Message('Premium Millionaire - You just signed up!'))
                ->setFrom('morteza_faraji@email.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'user/emails/signed-up.html.twig',
                        [
                            'userConfirmationHash' => base64_encode($user->getId()),
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
    public function reset(EntityManagerInterface $em, Request $request, \Swift_Mailer $mailer)
    {
        $resetUser = new User();

        $form = $this->createFormBuilder($resetUser)
            ->add('email', TextType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()/* && $form->isValid()*/) {
            $resetUser = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $resetUser->getEmail()]);
            $random_password = bin2hex(random_bytes(10));
            $user->setPassword($this->passwordEncoder->encodePassword($user, $random_password));
            $entityManager->flush();

            $message = (new \Swift_Message('Premium Millionaire - Reset your password!'))
                ->setFrom('morteza_faraji@email.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'user/emails/reset.html.twig',
                        [
                            'newPassword' => $random_password,
                        ]
                    ),
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash(
                'success',
                'We just sent you a password reset instruction email!'
            );
               
            return $this->redirectToRoute('app_login');
        }
    
        return $this->render('user/reset.html.twig', [
            'form' => $form->createView(),
        ]);            
    } 
    
    /**
     * @Route("/confirm/{user_confirmation_hash}", name="user-confirm")
     */
    public function confirmUser(EntityManagerInterface $em, string $user_confirmation_hash)
    {        
        $id = base64_decode($user_confirmation_hash);
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if($user && $user->getStatus() == 'unconfirmed') {
            $user->setStatus('confirmed');
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('dashboard');
        }

        // $message = (new \Swift_Message('Premium Millionaire - You just signed up!'))
        // ->setFrom('morteza_faraji@email.com')
        // ->setTo($user->getEmail())
        // ->setBody(
        //     $this->renderView(
        //         'user/emails/signed-up.html.twig',
        //         [
        //             // 'tempTicketId' => $tempTicketId,
        //             // 'bitcoinWallet' => $bitcoinWallet,
        //         ]
        //     ),
        //     'text/html'
        // );


        // $mailer->send($message);    
        return $this->render('user/confirm.html.twig');                    
    }     
}
