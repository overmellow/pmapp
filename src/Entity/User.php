<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"user" = "User", "participant" = "Participant"})
 * 
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ticket", mappedBy="User")
     */
    private $tickets;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\TempTicket", mappedBy="User", cascade={"persist", "remove"})
     */
    private $tempTicket;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TempTicket", mappedBy="User")
     */
    private $tempTickets;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->tempTickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $ticket->setUser($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getUser() === $this) {
                $ticket->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TempTicket[]
     */
    public function getTempTickets(): Collection
    {
        return $this->tempTickets;
    }

    public function addTempTicket(TempTicket $tempTicket): self
    {
        if (!$this->tempTickets->contains($tempTicket)) {
            $this->tempTickets[] = $tempTicket;
            $tempTicket->setUser($this);
        }

        return $this;
    }

    public function removeTempTicket(TempTicket $tempTicket): self
    {
        if ($this->tempTickets->contains($tempTicket)) {
            $this->tempTickets->removeElement($tempTicket);
            // set the owning side to null (unless already changed)
            if ($tempTicket->getUser() === $this) {
                $tempTicket->setUser(null);
            }
        }

        return $this;
    }

    // public function getTempTicket(): ?TempTicket
    // {
    //     return $this->tempTicket;
    // }

    // public function setTempTicket(TempTicket $tempTicket): self
    // {
    //     $this->tempTicket = $tempTicket;

    //     // set the owning side of the relation if necessary
    //     if ($this !== $tempTicket->getUser()) {
    //         $tempTicket->setUser($this);
    //     }

    //     return $this;
    // }
}
