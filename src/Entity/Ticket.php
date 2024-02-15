<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\Ticket\ArchivedController;
use App\Repository\TicketRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\OpenApi\Model;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            normalizationContext: ['groups' => ['Ticket:Read']],
            denormalizationContext: ['groups' => ['Ticket:Write']],
        ),
        new Put(
            normalizationContext: ['groups' => ['Ticket:Write']],
            denormalizationContext: ['groups' => ['Ticket:Write']],
        ),
        new Get(
            normalizationContext: ['groups' => ['Ticket:Read']],
        ),
        new Patch(
            uriTemplate: '/tickets/{id}/archived',
            controller: ArchivedController::class,
            denormalizationContext: ['groups' => ['Ticket:Archived']],
            read: false,
            name: 'archived_ticket'
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['Ticket:Read']],
        )
    ]
)]
#[ApiResource(
    uriTemplate: '/profiles/{id}/tickets',
    operations: [
        new GetCollection(
            openapi: new Model\Operation(
                summary: 'Retrieves the collection of Ticket resources by profile',
                description: 'Retrieves the collection of Ticket resources by profile',
            ),
            normalizationContext: ['groups' => ['Ticket:Read']],
        )
    ],
    uriVariables: [
        'id' => new Link(fromProperty: 'tickets', fromClass: Profile::class)
    ]
)]
#[ApiResource(
    uriTemplate: '/profiles/{profileID}/ticket/{id}',
    operations: [
        new Get(
            openapi: new Model\Operation(
                summary: 'Retrieves a Ticket resources by profile',
                description: 'Retrieves a Ticket resources by profile',
            ),
            normalizationContext: ['groups' => ['Ticket:Read']],
        )
    ],
    uriVariables: [
        'profileID' => new Link(fromProperty: 'tickets', fromClass: Profile::class),
        'id' => new Link(fromClass: Ticket::class),
    ]
)]
#[ApiResource(
    uriTemplate: '/priorities/{id}/tickets',
    operations: [
        new GetCollection(
            openapi: new Model\Operation(
                summary: 'Retrieves the collection of Ticket resources by priority',
                description: 'Retrieves the collection of Ticket resources by priority',
            ),
            normalizationContext: ['groups' => ['Ticket:Read']],
        )
    ],
    uriVariables: [
        'id' => new Link(fromProperty: 'tickets', fromClass: Priority::class)
    ]
)]
#[ApiResource(
    uriTemplate: '/statuses/{id}/tickets',
    operations: [
        new GetCollection(
            openapi: new Model\Operation(
                summary: 'Retrieves the collection of Ticket resources by status',
                description: 'Retrieves the collection of Ticket resources by status',
            ),
            normalizationContext: ['groups' => ['Ticket:Read']],
        )
    ],
    uriVariables: [
        'id' => new Link(fromProperty: 'tickets', fromClass: Status::class)
    ]
)]
#[ApiResource(
    uriTemplate: '/tickets/{id}/status',
    operations: [
        new Patch(
            openapi: new Model\Operation(
                summary: 'Edit status of Ticket resources',
                description: 'Edit status of Ticket resources',
            ),
            normalizationContext: ['groups' => ['Ticket:Read']],
            denormalizationContext: ['groups' => ['Ticket:Edit:Status']],
        )
    ],
    uriVariables: [
        'id' => new Link(fromClass: Ticket::class)
    ]
)]
#[ApiResource(
    uriTemplate: '/tickets/{id}/priority',
    operations: [
        new Patch(
            openapi: new Model\Operation(
                summary: 'Edit priority of Ticket resources',
                description: 'Edit priority of Ticket resources',
            ),
            normalizationContext: ['groups' => ['Ticket:Read']],
            denormalizationContext: ['groups' => ['Ticket:Edit:Priority']],
        )
    ],
    uriVariables: [
        'id' => new Link(fromClass: Ticket::class)
    ]
)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['Ticket:Write', 'Ticket:Read'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['Ticket:Write', 'Ticket:Read'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['Ticket:Read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['Ticket:Read'])]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['Ticket:Read', 'Ticket:Edit:Status'])]
    private ?Status $status = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Ticket:Write', 'Ticket:Read', 'Ticket:Edit:Priority'])]
    private ?Priority $priority = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Ticket:Write', 'Ticket:Read'])]
    private ?Profile $profile = null;

    #[ORM\Column]
    #[Groups(['Ticket:Read', 'Ticket:Archived'])]
    private ?bool $archived = null;

    public function __construct()
    {
        $this->archived = false;
        $this->createdAt = new \DateTimeImmutable();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPriority(): ?Priority
    {
        return $this->priority;
    }

    public function setPriority(?Priority $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): static
    {
        $this->profile = $profile;

        return $this;
    }

    public function isArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): static
    {
        $this->archived = $archived;

        return $this;
    }
}
