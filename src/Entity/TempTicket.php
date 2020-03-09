<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TempTicketRepository")
 */
class TempTicket
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

    // /**
    //  * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="tempTicket", cascade={"persist", "remove"})
    //  * @ORM\JoinColumn(nullable=false)
    //  */
    // private $User;

    // /**
    //  * @ORM\OneToOne(targetEntity="App\Entity\Lottery", inversedBy="tempTicket", cascade={"persist", "remove"})
    //  * @ORM\JoinColumn(nullable=false)
    //  */
    // private $Lottery;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tempTickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Lottery", inversedBy="tempTickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Lottery;

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

    // public function getUser(): ?User
    // {
    //     return $this->User;
    // }

    // public function setUser(User $User): self
    // {
    //     $this->User = $User;

    //     return $this;
    // }

    // public function getLottery(): ?Lottery
    // {
    //     return $this->Lottery;
    // }

    // public function setLottery(Lottery $Lottery): self
    // {
    //     $this->Lottery = $Lottery;

    //     return $this;
    // }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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
