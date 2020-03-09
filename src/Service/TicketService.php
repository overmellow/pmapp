<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Ticket;

class TicketService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
       $this->em = $em;
    }

    private function getDoctrine()
    {
        return $this->em;
    }

    public function verifyTicketPayment(Ticket $ticket)
    {
        return false;
    }

    // public function convertTempTicketToTicket(TempTicket $tempTicket)
    // {

    // }

}