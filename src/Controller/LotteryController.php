<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Lottery;
use App\Form\Type\LotteryType;


class LotteryController extends AbstractController
{
    /**
     * @Route("/admin/lottery", name="lottery")
     */
    public function index(EntityManagerInterface $em)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $lotteries = $entityManager->getRepository(Lottery::class)->findAll();
        // $lotteries = $em->findAll();
        return $this->render('lottery/index.html.twig', [
            'controller_name' => 'LotteryController',
            'lotteries' => $lotteries,
        ]);
    }

    /**
     * @Route("/admin/lottery/add", name="lottery-add")
     */
    public function addLottery(EntityManagerInterface $em, Request $request)
    {
        $lottery = new Lottery();

        $form = $this->createForm(LotteryType::class, $lottery);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $lottery = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lottery);
            $entityManager->flush();
    
            return $this->redirectToRoute('');
        }
    
        return $this->render('lottery/new.html.twig', [
            'form' => $form->createView(),
        ]);            
    }
    
    /**
     * @Route("/admin/lottery/detail/{id}", name="lottery-details")
     */
    public function detailsLottery(EntityManagerInterface $em, Request $request, string $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $lottery = $entityManager->getRepository(Lottery::class)->find($id);
        return $this->render('lottery/detail.html.twig', [
            'controller_name' => 'LotteryController',
            'lottery' => $lottery,
        ]);          
    }
    
}
