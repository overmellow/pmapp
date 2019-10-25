<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Lottery;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(EntityManagerInterface $em)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $lotteries = $entityManager->getRepository(Lottery::class)->findAll();
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'lotteries' => $lotteries,
        ]);
    }
    
    /**
     * @Route("/lotteries", name="lotteries")
     */
    public function allLotteries(EntityManagerInterface $em)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $lotteries = $entityManager->getRepository(Lottery::class)->findAll();
        return $this->render('default/lotteries.html.twig', [
            'controller_name' => 'DefaultController',
            'lotteries' => $lotteries,
        ]);
    }
    
    /**
     * @Route("/results", name="results")
     */
    public function results(EntityManagerInterface $em)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $lotteries = $entityManager->getRepository(Lottery::class)->findAll();
        return $this->render('default/results.html.twig', [
            'controller_name' => 'DefaultController',
            'lotteries' => $lotteries,
        ]);
    }
    
    /**
     * @Route("/how-to-play", name="how-to-play")
     */
    public function howToPlay()
    {
        return $this->render('default/how-to-play.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    
    /**
     * @Route("/faq", name="faq")
     */
    public function faq()
    {
        return $this->render('default/faq.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/about-us", name="about-us")
     */
    public function aboutUs()
    {
        return $this->render('default/about-us.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/contact-us", name="contact-us")
     */
    public function contactUs()
    {
        return $this->render('default/contact-us.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
