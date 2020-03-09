<?php
namespace App\Service;

use App\Library\Bitcoin;

class BitcoinService
{
    private $bitcoin;
    private $user;
    private $password;
    private $host;
    private $port;
    private $wallet;

    public function __construct()
    {
        $this->user = $_ENV['BITCOIN_USER'];
        $this->password = $_ENV['BITCOIN_USER_PASSWORD'];
        $this->host = $_ENV['BITCOIN_HOST'];
        $this->port = $_ENV['BITCOIN_TEST_PORT'];
        $this->wallet = $_ENV['BITCOIN_WALLET'];
        $this->bitcoin = new Bitcoin($this->user, $this->password, $this->host, $this->port, $this->wallet);
    }

    public function getBalance()
    {
        return $this->bitcoin->getbalance();
    }

    public function getNewAddress()
    {
        return $this->bitcoin->getnewaddress();
    }

    public function getTransaction(String $tx)
    {
        return $this->bitcoin->gettransaction($tx);
    }

    public function getAddressByTransaction(String $tx)
    {
        $transaction = $this->bitcoin->gettransaction($tx);
        return $transaction['details'][0]['address'];
    }

}