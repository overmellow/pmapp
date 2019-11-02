<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LotteryRepository")
 */
class Lottery
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $LotteryNumber;

    /**
     * @ORM\Column(type="integer")
     */
    private $Size;

    /**
     * @ORM\Column(type="float")
     */
    private $ticket_amount;

    /**
     * @ORM\Column(type="float")
     */
    private $jackpot;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $StartAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CloseAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Active;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ticket", mappedBy="Lottery")
     */
    private $tickets;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLotteryNumber(): ?string
    {
        return $this->LotteryNumber;
    }

    public function setLotteryNumber(string $LotteryNumber): self
    {
        $this->LotteryNumber = $LotteryNumber;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->Size;
    }

    public function setSize(int $Size): self
    {
        $this->Size = $Size;

        return $this;
    }

    public function getTicketAmount(): ?float
    {
        return $this->ticket_amount;
    }

    public function setTicketAmount(float $ticket_amount): self
    {
        $this->ticket_amount = $ticket_amount;

        return $this;
    }

    public function getJackpot(): ?float
    {
        return $this->jackpot;
    }

    public function setJackpot(float $jackpot): self
    {
        $this->jackpot = $jackpot;

        return $this;
    }
    
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->StartAt;
    }

    public function setStartAt(\DateTimeInterface $StartAt): self
    {
        $this->StartAt = $StartAt;

        return $this;
    }

    public function getCloseAt(): ?\DateTimeInterface
    {
        return $this->CloseAt;
    }

    public function setCloseAt(\DateTimeInterface $CloseAt): self
    {
        $this->CloseAt = $CloseAt;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->Active;
    }

    public function setActive(bool $Active): self
    {
        $this->Active = $Active;

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setLottery($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getLottery() === $this) {
                $ticket->setLottery(null);
            }
        }

        return $this;
    }
}
