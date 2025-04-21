<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table(name: 'comments')]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private string $content = '';

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private User $author;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private Report $report;

    #[ORM\ManyToOne(targetEntity: self::class, cascade: ['persist', 'remove'], inversedBy: 'replies')]
    private ?Comment $parentComment = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parentComment', cascade: ['persist', 'remove'])]
    private Collection $childComments;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->childComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;
        return $this;
    }

    public function getReport(): Report
    {
        return $this->report;
    }

    public function setReport(Report $report): self
    {
        $this->report = $report;
        return $this;
    }

    public function getParentComment(): ?self
    {
        return $this->parentComment;
    }

    public function setParentComment(?self $parentComment): self
    {
        $this->parentComment = $parentComment;
        return $this;
    }

    public function getChildComments(): Collection
    {
        return $this->childComments;
    }

    public function addChildComment(self $comment): self
    {
        if (!$this->childComments->contains($comment)) {
            $this->childComments->add($comment);
            $comment->setParentComment($this);
        }

        return $this;
    }

    public function removeChildComment(self $comment): self
    {
        if ($this->childComments->removeElement($comment) && $comment->getParentComment() === $this) {
            $comment->setParentComment(null);
        }

        return $this;
    }

    public function addComment(self $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setParentComment($this);
        }

        return $this;
    }
}