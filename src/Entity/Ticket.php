<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 */
class Ticket
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $TicketNumber;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *  min = 64,
     *  max = 64,
     *  exactMessage = "Your bitcoin transaction hash value is not valid!",     
     * )
     */
    private $BitcoinTransactionNumber;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Lottery", inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Lottery;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"}, nullable=true)
     */
    private $PurchasedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $BitcoinTransactionDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $Amount;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $WalletAddress;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTicketNumber(): ?int
    {
        return $this->TicketNumber;
    }

    public function setTicketNumber(int $TicketNumber): self
    {
        $this->TicketNumber = $TicketNumber;

        return $this;
    }

    public function getBitcoinTransactionNumber(): ?string
    {
        return $this->BitcoinTransactionNumber;
    }

    public function setBitcoinTransactionNumber(string $BitcoinTransactionNumber): self
    {
        $this->BitcoinTransactionNumber = $BitcoinTransactionNumber;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getLottery(): ?Lottery
    {
        return $this->Lottery;
    }

    public function setLottery(?Lottery $Lottery): self
    {
        $this->Lottery = $Lottery;

        return $this;
    }

    public function getPurchasedAt(): ?\DateTimeInterface
    {
        return $this->PurchasedAt;
    }

    public function setPurchasedAt(\DateTimeInterface $PurchasedAt): self
    {
        $this->PurchasedAt = $PurchasedAt;

        return $this;
    }

    public function getBitcoinTransactionDate(): ?\DateTimeInterface
    {
        return $this->BitcoinTransactionDate;
    }

    public function setBitcoinTransactionDate(\DateTimeInterface $BitcoinTransactionDate): self
    {
        $this->BitcoinTransactionDate = $BitcoinTransactionDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->Amount;
    }

    public function setAmount(string $Amount): self
    {
        $this->Amount = $Amount;

        return $this;
    }

    public function getWalletAddress(): ?string
    {
        return $this->WalletAddress;
    }

    public function setWalletAddress(string $WalletAddress): self
    {
        $this->WalletAddress = $WalletAddress;

        return $this;
    }
}
