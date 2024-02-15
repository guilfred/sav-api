<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\StatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection()
    ],
    security: "is_granted('ROLE_SUPER_ADMIN')",
    securityMessage: 'Accés à cette ressource est strictement réservé aux administrateurs !'
)]
class Status
{
    const STATUS = [
        'pending' => 'En attente de traitement',
        'in_progress' =>'En cours',
        'completed' => 'Résolu',
        'rejected' => 'Rejeté',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['Ticket:Read'])]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'status', targetEntity: Ticket::class)]
    private Collection $tickets;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): static
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setStatus($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): static
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getStatus() === $this) {
                $ticket->setStatus(null);
            }
        }

        return $this;
    }
}
