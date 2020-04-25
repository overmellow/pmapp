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
use App\Service\BitcoinService;

use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;

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
    public function play(Request $request, \Swift_Mailer $mailer, BitcoinService $bitcoinService, string $id)
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
            $tempTicket->setWalletAddress($bitcoinService->getNewAddress());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tempTicket);
            $entityManager->persist($lottery);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'primary',
                'We have reserved your ticket, please make your Bitcoin payment to the follow wallet'
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
                            'bitcoinWallet' => $tempTicket->getWalletAddress(),
                            'ticketNumber' => $tempTicket->getTicketNumber(),
                            'lotteryNumber' => $tempTicket->getLottery()->getLotteryNumber(),
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
     * @Route("/dashboard/play/check/{lottery_id}/{ticket_number}/{timestamp}", name="ticket-number-check")
     */
    public function checkReservedTicketNumber(Request $request, string $lottery_id, string $ticket_number, string $timestamp)
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
            return new Response(json_encode(['status' => false, 'timestamp' =>  $timestamp ]), 404, array('Content-Type' => 'application/json'));
        }
        return new Response(json_encode(['status' => true, 'timestamp' => $timestamp ]), 200, array('Content-Type' => 'application/json'));
    }

    /**
     * @Route("/dashboard/play/pay/bitcoin", name="pay-bitcoin")
     */
    public function postBitcoinTransactionNumber(Request $request, BitcoinService $bitcoinService, PublisherInterface $publisher)
    {
        $tx = $request->query->get('tx');
        $tx_wallet_address = $bitcoinService->getAddressByTransaction($tx);
        $entityManager = $this->getDoctrine()->getManager();
        $tempTicket = $entityManager->getRepository(TempTicket::class)->findOneBy(
            array('WalletAddress' => $tx_wallet_address),
        );

        if(!$tempTicket)
        {
            throw new \Exception('Resource not found!');
        }

        $ticket = new Ticket();
        $ticket->setUser($tempTicket->getUser());
        $ticket->setLottery($tempTicket->getLottery());
        $ticket->setTicketNumber($tempTicket->getTicketNumber());
        $ticket->setWalletAddress($tempTicket->getWalletAddress());
        $ticket->setPurchasedAt(new \DateTime());
        $ticket->setBitcoinTransactionDate(new \DateTime());
        $ticket->setAmount($tempTicket->getLottery()->getTicketAmount());
        $ticket->setBitcoinTransactionNumber($tx);
        $ticket->setStatus('verified');

        $entityManager->persist($ticket);
        $entityManager->remove($tempTicket);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Your Bitcoin payment has been received! We\'ve sent you an email including your ticket and payment reciept!'
        );

        $update = new Update(
            '/dashboard/play/pay/bitcoin/paid/walletaddress/' . $tx_wallet_address,
            json_encode(['status' => 'paid'])
        );

        // The Publisher service is an invokable object
        $publisher($update);

        return new Response('true', 200, array('Content-Type' => 'text/html'));
    }    

    /**
     * @Route("/dashboard/play/pay/{id}", name="pay")
     */
    public function pay(Request $request, TicketService $ticketService, BitcoinService $bitcoinService, string $id)
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
        $ticket->setWalletAddress($tempTicket->getWalletAddress());

        // $bitcoinWallet = $_ENV['BITCOIN_WALLET'];
        // $bitcoinWallet = $bitcoinService->getNewAddress();

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
                'Your Bitcoin payment has been received! We\'ve sent you an email including your ticket and payment reciept!'
            );
            
            return $this->redirectToRoute('ticket', array('id' => $ticket->getId()));
        }
    
        return $this->render('dashboard/pay.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'lottery' => $lottery,
            'ticket' => $ticket,
            // 'bitcoinWallet' => $bitcoinWallet,
            'tempTicketCreatedAt' => $tempTicket->getCreatedAt(),
            'MERCURE_PUBLISH_URL' => $_ENV['MERCURE_PUBLISH_URL'],
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
     * @Route("/dashboard/play/pay/bitcoin/paid", name="paid-bitcoin")
     */
    public function informBitcoinPaid(Request $request, BitcoinService $bitcoinService)
    {
        $tx_wallet_address = $request->query->get("walletaddress");        
        // $time = date('r');
        // echo "data: The server time is: {$time}\n\n";
        
        $entityManager = $this->getDoctrine()->getManager();
        $ticket = $entityManager->getRepository(Ticket::class)->findOneBy(
            array('WalletAddress' => $tx_wallet_address),
        );

        // echo 'data: {paid: true, ticket: 123} \n\n';
        echo 'data: hello\n\n';
        // echo 'data: {\n';
        // echo 'data: "msg": "hello world",\n';
        // echo 'data: "id": 12345\n';
        // echo 'data: }\n\n';

        if($ticket)
        {
            echo 'data: {"paid": true, "ticket": ' . $ticket.id .'} \n\n';
        }

        flush();

        return new Response('', 200, array('Content-Type' => 'text/event-stream', 'Cache-Control' => 'no-cache'));
    }
}
