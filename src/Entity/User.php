<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
class User implements PasswordAuthenticatedUserInterface
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

    #[ORM\OneToMany(targetEntity: Friendship::class, mappedBy: 'requestingUser')]
    private Collection $sentFriendRequests;

    #[ORM\OneToMany(targetEntity: Friendship::class, mappedBy: 'receivingUser')]
    private Collection $receivedFriendRequests;

    #[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'recipient')]
    private Collection $notifications;

    #[ORM\OneToMany(targetEntity: Offer::class, mappedBy: 'creator')]
    private Collection $offers;

    #[ORM\OneToMany(targetEntity: Report::class, mappedBy: 'author')]
    private Collection $reports;

    #[ORM\OneToMany(targetEntity: ZaMbaHoento::class, mappedBy: 'user')]
    private Collection $zaMbaHoentos;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->votes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->sentFriendRequests = new ArrayCollection();
        $this->receivedFriendRequests = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->offers = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->zaMbaHoentos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
        return array_unique(array_merge($this->roles, ['ROLE_USER']));
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

    public function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(?string $avatarUrl): self
    {
        $this->avatarUrl = $avatarUrl;
        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): self
    {
        $this->points = $points;
        return $this;
    }

    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Voting $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes->add($vote);
            $vote->setUser($this);
        }
        return $this;
    }

    public function removeVote(Voting $vote): self
    {
        if ($this->votes->removeElement($vote) && $vote->getUser() === $this) {
            $vote->setUser(null);
        }
        return $this;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setAuthor($this);
        }
        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment) && $comment->getAuthor() === $this) {
            $comment->setAuthor(null);
        }
        return $this;
    }

    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setAuthor($this);
        }
        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review) && $review->getAuthor() === $this) {
            $review->setAuthor(null);
        }
        return $this;
    }

    public function getSentFriendRequests(): Collection
    {
        return $this->sentFriendRequests;
    }

    public function getReceivedFriendRequests(): Collection
    {
        return $this->receivedFriendRequests;
    }

    public function addFriendRequest(Friendship $friendship, bool $isRequester = true): self
    {
        $collection = $isRequester ? $this->sentFriendRequests : $this->receivedFriendRequests;
        if (!$collection->contains($friendship)) {
            $collection->add($friendship);
            if ($isRequester) {
                $friendship->setRequestingUser($this);
            } else {
                $friendship->setReceivingUser($this);
            }
        }
        return $this;
    }

    public function removeFriendRequest(Friendship $friendship, bool $isRequester = true): self
    {
        $collection = $isRequester ? $this->sentFriendRequests : $this->receivedFriendRequests;
        if ($collection->removeElement($friendship)) {
            if ($isRequester && $friendship->getRequestingUser() === $this) {
                $friendship->setRequestingUser(null);
            } elseif (!$isRequester && $friendship->getReceivingUser() === $this) {
                $friendship->setReceivingUser(null);
            }
        }
        return $this;
    }

    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setRecipient($this);
        }
        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification) && $notification->getRecipient() === $this) {
            $notification->setRecipient(null);
        }
        return $this;
    }

    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers->add($offer);
            $offer->setCreator($this);
        }
        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->removeElement($offer) && $offer->getCreator() === $this) {
            $offer->setCreator(null);
        }
        return $this;
    }

    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports->add($report);
            $report->setAuthor($this);
        }
        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->removeElement($report) && $report->getAuthor() === $this) {
            $report->setAuthor(null);
        }
        return $this;
    }

    public function getZaMbaHoentos(): Collection
    {
        return $this->zaMbaHoentos;
    }

    public function addZaMbaHoento(ZaMbaHoento $zaMbaHoento): self
    {
        if (!$this->zaMbaHoentos->contains($zaMbaHoento)) {
            $this->zaMbaHoentos->add($zaMbaHoento);
            $zaMbaHoento->setUser($this);
        }
        return $this;
    }

    public function removeZaMbaHoento(ZaMbaHoento $zaMbaHoento): self
    {
        if ($this->zaMbaHoentos->removeElement($zaMbaHoento) && $zaMbaHoento->getUser() === $this) {
            $zaMbaHoento->setUser(null);
        }
        return $this;
    }
}