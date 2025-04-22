<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private string $password = '';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fullName = null;

    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $username = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    #[ORM\OneToMany(targetEntity: Report::class, mappedBy: 'user')]
    private Collection $traffics;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $avatarUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(nullable: true)]
    private ?int $points = null;

    #[ORM\OneToMany(targetEntity: Voting::class, mappedBy: 'user')]
    private Collection $votes;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'author')]
    private Collection $comments;

    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'author')]
    private Collection $reviews;

    #[ORM\OneToMany(targetEntity: Friendship::class, mappedBy: 'userFriends')]
    private Collection $friendships;

    #[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'recipient')]
    private Collection $notifications;

    #[ORM\OneToMany(targetEntity: Offer::class, mappedBy: 'creator')]
    private Collection $offers;

    #[ORM\OneToMany(targetEntity: Report::class, mappedBy: 'author')]
    private Collection $reports;

    #[ORM\OneToMany(targetEntity: CanYouGiveMeARide::class, mappedBy: 'user')]
    private Collection $canYouGiveARide;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable("now");
        $this->votes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->friendships = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->offers = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->canYouGiveARide = new ArrayCollection();
        $this->traffics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): self
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getTraffics(): Collection
    {
        return $this->traffics;
    }

    public function setTraffics(Collection $traffics): void
    {
        $this->traffics = $traffics;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(?string $avatarUrl): void
    {
        $this->avatarUrl = $avatarUrl;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): void
    {
        $this->gender = $gender;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): void
    {
        $this->points = $points;
    }

    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function setVotes(Collection $votes): void
    {
        $this->votes = $votes;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function setComments(Collection $comments): void
    {
        $this->comments = $comments;
    }

    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function setReviews(Collection $reviews): void
    {
        $this->reviews = $reviews;
    }

    public function getFriendships(): Collection
    {
        return $this->friendships;
    }

    public function setFriendships(Collection $friendships): void
    {
        $this->friendships = $friendships;
    }

    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function setNotifications(Collection $notifications): void
    {
        $this->notifications = $notifications;
    }

    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function setOffers(Collection $offers): void
    {
        $this->offers = $offers;
    }

    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function setReports(Collection $reports): void
    {
        $this->reports = $reports;
    }

    public function getCanYouGiveARide(): Collection
    {
        return $this->canYouGiveARide;
    }

    public function setCanYouGiveARide(Collection $canYouGiveARide): void
    {
        $this->canYouGiveARide = $canYouGiveARide;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email ?? $this->username ?? (string)$this->id;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setRecipient($this);
        }

        return $this;
    }
}