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

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Service\TicketService;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index()
    {
        $user = $this->getUser();

        $entityManager = $this->getDoctrine()->getManager();
        $lotteries = $entityManager->getRepository(Lottery::class)->findBy(
            array('status' => 'started'),
        );
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
    public function play(Request $request, \Swift_Mailer $mailer, string $id)
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
            $tempTicket->setAmount($lottery->getTicketAmount());
            $tempTicket->setStatus('pending');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tempTicket);
            $entityManager->persist($lottery);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'We have reserved your ticket, please make your payment!'
            );

            $tempTicketId = $tempTicket->getId(); 

            $bitcoinWallet = $_ENV['BITCOIN_WALLET'];
            $app_email = $_ENV['APP_EMAIL'];
    
            $message = (new \Swift_Message('Bitcoin Payment Instruction'))
                ->setFrom($app_email)
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'dashboard/emails/temp-ticket.html.twig',
                        [
                            'tempTicketId' => $tempTicketId,
                            'bitcoinWallet' => $bitcoinWallet,
                        ]
                    ),
                    'text/html'
                );
    
            $mailer->send($message);
            
            // $response = $this->forward('App\Controller\DashboardController::pay', [
            //     'id'  => $tempTicketId,
            // ]);            
            // return $response;
            
            return $this->redirectToRoute('pay', array('id' => $tempTicketId));
        }
    
        return $this->render('dashboard/play.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'lottery' => $lottery,
        ]);
    }
    
    /**
     * @Route("/dashboard/play/check/{lottery_id}/{ticket_number}", name="ticket-number-check")
     */
    public function checkReservedTicketNumber(Request $request, string $lottery_id, string $ticket_number)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tempTicket = $entityManager->getRepository(TempTicket::class)->findOneBy([
            'Lottery' => $lottery_id,
            'TicketNumber' => $ticket_number
        ]);
        $ticket = $entityManager->getRepository(Ticket::class)->findOneBy([
            'Lottery' => $lottery_id,
            'TicketNumber' => $ticket_number
        ]);

        if($tempTicket != null || $ticket != null){
            return new Response('false', 404, array('Content-Type' => 'text/html'));
        }
        return new Response('true', 200, array('Content-Type' => 'text/html'));
    }

    /**
     * @Route("/dashboard/play/pay/{id}", name="pay")
     */
    public function pay(Request $request, TicketService $ticketService, string $id)
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

        $bitcoinWallet = $_ENV['BITCOIN_WALLET'];

        $form = $this->createForm(TicketType::class, $ticket);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ticket = $form->getData();
            $ticket->setPurchasedAt(new \DateTime());
            $ticket->setBitcoinTransactionDate(new \DateTime());
            $ticket->setAmount($tempTicket->getLottery()->getTicketAmount());
            
            if($ticketService->verifyTicketPayment($ticket)){
                $ticket->setStatus('verified');
            } else {
                $ticket->setStatus('unverified');
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ticket);
            $entityManager->persist($user);
            $entityManager->remove($tempTicket);
            $entityManager->flush();            

            $this->addFlash(
                'success',
                'You\'ve paid for the ticket!'
            );
    
            return $this->redirectToRoute('dashboard');
        }
    
        return $this->render('dashboard/pay.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'lottery' => $lottery,
            'ticket' => $ticket,
            'bitcoinWallet' => $bitcoinWallet,
        ]);             

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
    
    /**
     * @Route("/test", name="test")
     */
    public function test(\Swift_Mailer $mailer){
        $bitcoinWallet = $_ENV['BITCOIN_WALLET'];
        $app_email = $_ENV['APP_EMAIL'];

        $message = (new \Swift_Message('Bitcoin Payment Instruction'))
            ->setFrom($app_email)
            ->setTo('morteza_faraji@yahoo.com')
            ->setBody(
                $this->renderView(
                    'dashboard/emails/test.html.twig',
                    []
                ),
                'text/html'
            );

        echo $mailer->send($message);
        
        return $this->render('dashboard/test.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
