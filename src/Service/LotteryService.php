<?php
namespace App\Service;

// use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Lottery;

class LotteryService
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

    public function isActive(String $lottery_id)
    {
        $entityManager = $this->getDoctrine();
        $lottery = $entityManager->getRepository(Lottery::class)->find($lottery_id);

        if(count($lottery->getTickets()) >= $lottery->getSize()) 
        {
            $lottery->setStatus('closed');
            $entityManager->persist($lottery);
            $entityManager->flush();
        }

        return;
    }
}
