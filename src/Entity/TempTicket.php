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

    /**
     * @ORM\Column(type="datetime")
     */
    private $PickedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $timer;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Lottery", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Lottery;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Status;

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

    public function getPickedAt(): ?\DateTimeInterface
    {
        return $this->PickedAt;
    }

    public function setPickedAt(\DateTimeInterface $PickedAt): self
    {
        $this->PickedAt = $PickedAt;

        return $this;
    }

    public function getTimer(): ?int
    {
        return $this->timer;
    }

    public function setTimer(int $timer): self
    {
        $this->timer = $timer;

        return $this;
    }

    public function getLottery(): ?Lottery
    {
        return $this->Lottery;
    }

    public function setLottery(Lottery $Lottery): self
    {
        $this->Lottery = $Lottery;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): self
    {
        $this->Status = $Status;

        return $this;
    }
}
