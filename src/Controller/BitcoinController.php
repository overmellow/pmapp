<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\BitcoinService;

class BitcoinController extends AbstractController
{
    /**
     * @Route("/admin/bitcoin", name="admin-bitcoin")
     */
    public function index(BitcoinService $bitcoinService)
    {
        return $this->render('bitcoin/index.html.twig', [
            'controller_name' => 'BitcoinController',
            'bitcoin_wallet_info' => $bitcoinService->getWalletInfo()
        ]);
    }
}
