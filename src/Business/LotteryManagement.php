<?php

namespace App\Business;

use Doctrine\ORM\Mapping as ORM;

class LotteryManagement
{
    public function isActive(String $lottery_id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $lottery = $entityManager->getRepository(Lottery::class)->find($lottery_id);

        if(count($lottery->getTickets()) >= $lottery->getSize()) 
        {
            $lottery->setActive(false);
            $entityManager->persist($lottery);
            $entityManager->flush();
        }

        return;
    }

}