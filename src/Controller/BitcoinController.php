<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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
    
    /**
     * @Route("/admin/bitcoin/address", name="admin-bitcoin-tx")
     */
    public function getAddress(BitcoinService $bitcoinService, Request $request)
    {
        $tx = $request->query->get('tx');
        echo $tx;
        $wallet_address = $bitcoinService->getAddressByTransaction($tx);
        echo $bitcoinService->displayError();
        
        return $this->render('bitcoin/tx.html.twig', [
            'controller_name' => 'BitcoinController',
            'tx' => $wallet_address
        ]);
    }       
}
