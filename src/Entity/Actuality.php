<?php

namespace App\Entity;

use App\Repository\ActualityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActualityRepository::class)]
class Actuality
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $lieu;

    #[ORM\Column(length: 255)]
    private string $type;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $photo = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $createdAt;

    #[ORM\OneToMany(targetEntity: Voting::class, mappedBy: 'actuality')]
    private Collection $vote;

    public function __construct()
    {
        $this->createdAt = new \DateTime("now");
        $this->vote = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Voting>
     */
    public function getVote(): Collection
    {
        return $this->vote;
    }

    public function addVote(Voting $vote): static
    {
        if (!$this->vote->contains($vote)) {
            $this->vote->add($vote);
            $vote->setActuality($this);
        }

        return $this;
    }

    public function removeVote(Voting $vote): static
    {
        if ($this->vote->removeElement($vote)) {
            if ($vote->getActuality() === $this) {
                $vote->setActuality(null);
            }
        }

        return $this;
    }
}
