<?php
namespace App\Entity;

use App\Repository\SummitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SummitRepository::class)]
class Summit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $locationName = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $eventDate = null;

    #[ORM\Column]
    private ?int $capacity = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = false;

    #[ORM\OneToMany(mappedBy: 'summit', targetEntity: Registration::class)]
    private Collection $registrations;

    public function __construct()
    {
        $this->registrations = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getCity(): ?string { return $this->city; }
    public function setCity(string $city): static { $this->city = $city; return $this; }
    public function getLocationName(): ?string { return $this->locationName; }
    public function setLocationName(string $locationName): static { $this->locationName = $locationName; return $this; }
    public function getAddress(): ?string { return $this->address; }
    public function setAddress(string $address): static { $this->address = $address; return $this; }
    public function getEventDate(): ?\DateTimeInterface { return $this->eventDate; }
    public function setEventDate(\DateTimeInterface $eventDate): static { $this->eventDate = $eventDate; return $this; }
    public function getCapacity(): ?int { return $this->capacity; }
    public function setCapacity(int $capacity): static { $this->capacity = $capacity; return $this; }
    public function isActive(): bool { return $this->isActive; }
    public function setIsActive(bool $isActive): static { $this->isActive = $isActive; return $this; }
    public function getRegistrations(): Collection { return $this->registrations; }

    public function getRemainingCapacity(): int
    {
        return $this->capacity - $this->registrations->count();
    }

    public function isFull(): bool
    {
        return $this->getRemainingCapacity() <= 0;
    }
}