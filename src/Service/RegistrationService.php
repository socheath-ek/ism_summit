<?php
namespace App\Service;

use App\Entity\Registration;
use App\Entity\Summit;
use App\Repository\SummitRepository;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationService
{
    public function __construct(
        private EntityManagerInterface $em,
        private SummitRepository $summitRepository
    ) {}

    public function registerGuest(Registration $registration, Summit $summit): bool
    {
        if ($summit->isFull()) {
            return false;
        }
        $registration->setSummit($summit);
        $this->em->persist($registration);
        $this->em->flush();
        return true;
    }

    public function cancelRegistration(Registration $registration): void
    {
        $registration->setStatus('cancelled');
        $this->em->flush();
    }
}