<?php

namespace App\Entity;

use App\Repository\ZaMbaHoentoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ZaMbaHoentoRepository::class)]
#[ORM\Table(name: 'za_mba_hoentos')]
class ZaMbaHoento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $departureLocation = '';

    #[ORM\Column(length: 255)]
    private string $arrivalLocation = '';

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $departureDate = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(inversedBy: 'zaMbaHoentos')]
    #[ORM\JoinColumn(nullable: false)]
    private User $creator;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactInfo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $exactLocation = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $seatCount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $preferences = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepartureLocation(): string
    {
        return $this->departureLocation;
    }

    public function setDepartureLocation(string $departureLocation): self
    {
        $this->departureLocation = $departureLocation;
        return $this;
    }

    public function getArrivalLocation(): string
    {
        return $this->arrivalLocation;
    }

    public function setArrivalLocation(string $arrivalLocation): self
    {
        $this->arrivalLocation = $arrivalLocation;
        return $this;
    }

    public function getDepartureDate(): ?\DateTimeImmutable
    {
        return $this->departureDate;
    }

    public function setDepartureDate(?\DateTimeImmutable $departureDate): self
    {
        $this->departureDate = $departureDate;
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

    public function getCreator(): User
    {
        return $this->creator;
    }

    public function setCreator(User $creator): self
    {
        $this->creator = $creator;
        return $this;
    }

    public function getContactInfo(): ?string
    {
        return $this->contactInfo;
    }

    public function setContactInfo(?string $contactInfo): self
    {
        $this->contactInfo = $contactInfo;
        return $this;
    }

    public function getExactLocation(): ?string
    {
        return $this->exactLocation;
    }

    public function setExactLocation(?string $exactLocation): self
    {
        $this->exactLocation = $exactLocation;
        return $this;
    }

    public function getSeatCount(): ?int
    {
        return $this->seatCount;
    }

    public function setSeatCount(?int $seatCount): self
    {
        $this->seatCount = $seatCount;
        return $this;
    }

    public function getPreferences(): ?string
    {
        return $this->preferences;
    }

    public function setPreferences(?string $preferences): self
    {
        $this->preferences = $preferences;
        return $this;
    }
}