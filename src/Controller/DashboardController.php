<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Lottery;
use App\Entity\Ticket;
use App\Entity\User;
use App\Entity\TempTicket;
use App\Form\TicketType;
use App\Form\TempTicketType;

use Symfony\Component\HttpFoundation\Request;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index()
    {
        $user = $this->getUser();

        $entityManager = $this->getDoctrine()->getManager();
        $lotteries = $entityManager->getRepository(Lottery::class)->findall();
        //$myUser = $entityManager-getRepository(User::class)->find($user->getId());
        $tickets = $user->getTickets();
        $tempTickets = $user->getTempTickets();
        //$tickets = $entityManager->getRepository(Ticket::class)->findBy(array('user_id' => $user->getId()));
        //$tickets = $entityManager->getRepository(Ticket::class)->findAll();
        // $tempTickets = $entityManager->getRepository(TempTicket::class)->findAll();

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'user' => $user,
            'lotteries' => $lotteries,
            'tickets' => $tickets,
            'tempTickets' => $tempTickets,
        ]);
    }

    /**
     * @Route("/dashboard/play/{id}", name="play")
     */
    public function play(Request $request, string $id)
    {
        $user = $this->getUser();

        $entityManager = $this->getDoctrine()->getManager();
        $lottery = $entityManager->getRepository(Lottery::class)->find($id);

        $tempTicket = new TempTicket();
        $tempTicket->setUser($user);
        $tempTicket->setLottery($lottery);

        $form = $this->createForm(TempTicketType::class, $tempTicket);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $tempTicket = $form->getData();
            $tempTicket->setCreatedAt(new \DateTime());
            $tempTicket->setStatus('pending');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tempTicket);
            $entityManager->persist($lottery);
            $entityManager->persist($user);
            $entityManager->flush();
            $tempTicketId = $tempTicket->getId(); 
    
            $response = $this->forward('App\Controller\DashboardController::pay', [
                'id'  => $tempTicketId,
            ]);
        
            // ... further modify the response or return it directly
        
            return $response;
            //ßreturn $this->redirectToRoute('pay', ['id', $tempTicketId]);
        }
    
        return $this->render('dashboard/play.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'lottery' => $lottery,
        ]);
    }    

    /**
     * @Route("/dashboard/play/pay/{id}", name="pay")
     */
    public function pay(Request $request, string $id)
    {
        $user = $this->getUser();

        $entityManager = $this->getDoctrine()->getManager();
        // $lottery = $entityManager->getRepository(Lottery::class)->find($id);
        $tempTicket = $entityManager->getRepository(TempTicket::class)->find($id);
        $lottery = $tempTicket->getLottery();

        $ticket = new Ticket();
        $ticket->setUser($user);
        $ticket->setLottery($tempTicket->getLottery());
        $ticket->setTicketNumber($tempTicket->getTicketNumber());

        $form = $this->createForm(TicketType::class, $ticket);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $ticket = $form->getData();
            $ticket->setStatus('confirmed');
            $ticket->setPurchasedAt(new \DateTime());
            $ticket->setBitcoinTransactionDate(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ticket);
            //$entityManager->persist($lottery);
            $entityManager->persist($user);
            $entityManager->remove($tempTicket);
            $entityManager->flush();
    
            return $this->redirectToRoute('dashboard');
        }
    
        return $this->render('dashboard/pay.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'lottery' => $lottery,
        ]);             

        // return $this->render('dashboard/pay.html.twig', [
        //     'controller_name' => 'DashboardController',
        //     'user' => $user,
        //     'lottery' => $lottery,
        // ]);
    }

    /**
     * @Route("/dashboard/ticket/{id}", name="ticket")
     */
    public function ticket(string $id)
    {
        $user = $this->getUser();

        $entityManager = $this->getDoctrine()->getManager();
        $ticket = $entityManager->getRepository(Ticket::class)->find($id);

        return $this->render('dashboard/ticket.html.twig', [
            'controller_name' => 'DashboardController',
            'user' => $user,
            'ticket' => $ticket,
        ]);
    }
    
    /**
     * @Route("/dashboard/tempticket/{id}", name="tempticket")
     */
    public function tempTicket(string $id)
    {
        $user = $this->getUser();

        $entityManager = $this->getDoctrine()->getManager();
        $tempTicket = $entityManager->getRepository(TempTicket::class)->find($id);

        return $this->render('dashboard/temp-ticket.html.twig', [
            'controller_name' => 'DashboardController',
            'user' => $user,
            'tempTicket' => $tempTicket,
        ]);
    }  
}
