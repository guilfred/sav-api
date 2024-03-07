<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\Security\CreateAccountController;
use App\Controller\Security\MeController;
use App\Repository\AccountRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['Account:Get:Read']],
        ),
        new Get(
            uriTemplate: '/me',
            controller: MeController::class,
            normalizationContext: ['groups' => ['Account:Me']],
            security: 'is_granted("IS_AUTHENTICATED_FULLY")',
            read: false,
            name: 'me'
        ),
        new GetCollection(),
        new Post(
            uriTemplate: '/accounts',
            controller: CreateAccountController::class,
            normalizationContext: ['groups' => ['Account:Post:Read']],
            denormalizationContext: ['groups' => ['Account:Post:Write']],
            read: false,
            name: 'create_account'
        ),
    ],
)]
class Account implements UserInterface, PasswordAuthenticatedUserInterface
{
    const ROLES = [
        'client' => 'ROLE_CLIENT',
        'admin' => 'ROLE_ADMIN',
        'super_admin' => 'ROLE_SUPER_ADMIN'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups([
        'Account:Post:Write',
        'Account:Post:Read',
        'Account:Me'
    ])]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['Account:Post:Write'])]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $enabled = null;

    #[ORM\Column(nullable: true)]
    #[Groups([
        'Account:Me'
    ])]
    private ?\DateTimeImmutable $lastLogin = null;

    #[ORM\OneToOne(mappedBy: 'account', cascade: ['persist', 'remove'])]
    #[Groups([
        'Account:Post:Write',
        'Account:Post:Read',
        'Account:Me'
    ])]
    private ?Profile $profile = null;

    public function __construct()
    {
        $this->enabled = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): static
    {
        $role = strtoupper($role);

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeImmutable
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeImmutable $lastLogin): static
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(Profile $profile): static
    {
        // set the owning side of the relation if necessary
        if ($profile->getAccount() !== $this) {
            $profile->setAccount($this);
        }

        $this->profile = $profile;

        return $this;
    }
}
