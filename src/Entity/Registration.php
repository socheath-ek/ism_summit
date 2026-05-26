<?php
namespace App\Entity;

use App\Repository\RegistrationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegistrationRepository::class)]
class Registration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    private ?string $lastName = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 100)]
    private ?string $company = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $jobTitle = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 50)]
    private ?string $mealPreference = null;

    #[ORM\Column(type: 'boolean')]
    private bool $needsParking = false;

    #[ORM\Column(type: 'boolean')]
    private bool $needsAccommodation = false;

    #[ORM\Column(type: 'boolean')]
    private bool $newsletterConsent = false;

    #[ORM\Column(type: 'boolean')]
    private bool $dataProtectionConsent = false;

    #[ORM\Column(length: 20)]
    private string $status = 'confirmed';

    #[ORM\Column(length: 50, unique: true)]
    private ?string $ticketNumber = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $registeredAt = null;

    #[ORM\ManyToOne(inversedBy: 'registrations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Summit $summit = null;

    public function __construct()
    {
        $this->registeredAt = new \DateTime();
        $this->ticketNumber = 'ISM-' . strtoupper(substr(md5(uniqid()), 0, 8));
    }

    public function getId(): ?int { return $this->id; }
    public function getFirstName(): ?string { return $this->firstName; }
    public function setFirstName(string $firstName): static { $this->firstName = $firstName; return $this; }
    public function getLastName(): ?string { return $this->lastName; }
    public function setLastName(string $lastName): static { $this->lastName = $lastName; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): static { $this->email = $email; return $this; }
    public function getCompany(): ?string { return $this->company; }
    public function setCompany(string $company): static { $this->company = $company; return $this; }
    public function getJobTitle(): ?string { return $this->jobTitle; }
    public function setJobTitle(?string $jobTitle): static { $this->jobTitle = $jobTitle; return $this; }
    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $phone): static { $this->phone = $phone; return $this; }
    public function getMealPreference(): ?string { return $this->mealPreference; }
    public function setMealPreference(string $mealPreference): static { $this->mealPreference = $mealPreference; return $this; }
    public function isNeedsParking(): bool { return $this->needsParking; }
    public function setNeedsParking(bool $needsParking): static { $this->needsParking = $needsParking; return $this; }
    public function isNeedsAccommodation(): bool { return $this->needsAccommodation; }
    public function setNeedsAccommodation(bool $needsAccommodation): static { $this->needsAccommodation = $needsAccommodation; return $this; }
    public function isNewsletterConsent(): bool { return $this->newsletterConsent; }
    public function setNewsletterConsent(bool $newsletterConsent): static { $this->newsletterConsent = $newsletterConsent; return $this; }
    public function isDataProtectionConsent(): bool { return $this->dataProtectionConsent; }
    public function setDataProtectionConsent(bool $dataProtectionConsent): static { $this->dataProtectionConsent = $dataProtectionConsent; return $this; }
    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): static { $this->status = $status; return $this; }
    public function getTicketNumber(): ?string { return $this->ticketNumber; }
    public function setTicketNumber(string $ticketNumber): static { $this->ticketNumber = $ticketNumber; return $this; }
    public function getRegisteredAt(): ?\DateTimeInterface { return $this->registeredAt; }
    public function getSummit(): ?Summit { return $this->summit; }
    public function setSummit(?Summit $summit): static { $this->summit = $summit; return $this; }
    public function getFullName(): string { return $this->firstName . ' ' . $this->lastName; }
}