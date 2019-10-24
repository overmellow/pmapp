<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParticipantRepository")
 */
class Participant extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $LuckyCharm;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLuckyCharm(): ?string
    {
        return $this->LuckyCharm;
    }

    public function setLuckyCharm(?string $LuckyCharm): self
    {
        $this->LuckyCharm = $LuckyCharm;

        return $this;
    }
}
