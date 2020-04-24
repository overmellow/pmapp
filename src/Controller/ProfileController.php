<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserProfileType;
use App\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

use Symfony\Component\HttpFoundation\Request;

class ProfileController extends AbstractController
{
    private $userReposity;
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $userRepository)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
    }
    
    /**
     * @Route("/profile", name="profile")
     */
    public function index(EntityManagerInterface $em, Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserProfileType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            
            $this->addFlash(
                'success',
                'You\'ve reset your password successfuly!',
            );
    
            return $this->redirectToRoute('profile');
        }
    
        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function change_user_password(Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        $old_pwd = $request->get('user_profile[password][second]'); 
        $new_pwd = $request->get('user_profile[password][first]'); 
        $new_pwd_confirm = $request->get('user_profile[password][second]');
        $user = $this->getUser();
        $checkPass = $passwordEncoder->isPasswordValid($user, $old_pwd);
        if($checkPass === true) {
                
        } else {
            return new jsonresponse(array('error' => 'The current password is incorrect.'));
        }
     }
}
