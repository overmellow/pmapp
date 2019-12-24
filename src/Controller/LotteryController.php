<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Lottery;
use App\Form\LotteryType;
use App\Entity\Ticket;
use App\Form\TicketType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Business\LotteryManagement;

class LotteryController extends AbstractController
{
    /**
     * @Route("/admin/lotteries", name="admin-lotteries")
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
     * @Route("/admin/lottery/add", name="admin-lottery-add")
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
            $lottery->setCreatedAt(new \DateTime('now'));
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lottery);
            $entityManager->flush();
    
            return $this->redirectToRoute('admin-lotteries');
        }
    
        return $this->render('lottery/new.html.twig', [
            'form' => $form->createView(),
        ]);            
    }
    
    /**
     * @Route("/admin/lotteries/detail/{id}", name="admin-lottery-details")
     */
    public function detailsLottery(EntityManagerInterface $em, Request $request, string $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $lottery = $entityManager->getRepository(Lottery::class)->find($id);
        // return $this->render('lottery/detail.html.twig', [
        //     'controller_name' => 'LotteryController',
        //     'lottery' => $lottery,
        // ]); 
        
        $form = $this->createForm(LotteryType::class, $lottery);
    
        return $this->render('lottery/detail.html.twig', [
            'form' => $form->createView(),
            'lottery' => $lottery,
        ]);
    }

    /**
     * @Route("/admin/lotteries/{lotteryId}/ticket/{ticketId}/details", name="admin-lottery-ticket-details")
     */
    public function detailsTicket(EntityManagerInterface $em, Request $request, string $lotteryId, string $ticketId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $ticket = $entityManager->getRepository(Ticket::class)->find($ticketId);   
        
        // $form = $this->createForm(TicketType::class, $ticket);
        $form = $this->createFormBuilder($ticket)
            ->add('save', SubmitType::class, ['label' => 'Verify BitCoin Transaction Hash'])
            ->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $ticket->setStatus('verified');
            $entityManager->persist($ticket);
            $entityManager->flush();

            $lotteryManagement = new LotteryManagement;

            $lotteryManagement->isActive($ticket->getLottery->getId());
    
            return $this->redirectToRoute('admin-lotteries');
        }
    
        return $this->render('lottery/detail-ticket.html.twig', [
            'form' => $form->createView(),
            'ticket' => $ticket,
        ]);
    }
    
}
