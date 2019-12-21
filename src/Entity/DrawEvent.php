<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DrawEventRepository")
 */
class DrawEvent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $EventDate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $EventUrl;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Lottery", mappedBy="DrawEvent", cascade={"persist", "remove"})
     */
    private $lottery;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->EventDate;
    }

    public function setEventDate(\DateTimeInterface $EventDate): self
    {
        $this->EventDate = $EventDate;

        return $this;
    }

    public function getEventUrl(): ?string
    {
        return $this->EventUrl;
    }

    public function setEventUrl(?string $EventUrl): self
    {
        $this->EventUrl = $EventUrl;

        return $this;
    }

    public function getLottery(): ?Lottery
    {
        return $this->lottery;
    }

    public function setLottery(?Lottery $lottery): self
    {
        $this->lottery = $lottery;

        // set (or unset) the owning side of the relation if necessary
        $newDrawEvent = null === $lottery ? null : $this;
        if ($lottery->getDrawEvent() !== $newDrawEvent) {
            $lottery->setDrawEvent($newDrawEvent);
        }

        return $this;
    }
}
