<?php
namespace App\Repository;

use App\Entity\Registration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RegistrationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Registration::class);
    }

    public function findBySummitSorted(int $summitId, string $sortField = 'registeredAt', string $sortDir = 'DESC'): array
    {
        $allowed = ['firstName', 'lastName', 'email', 'company', 'registeredAt', 'status', 'mealPreference'];
        if (!in_array($sortField, $allowed)) $sortField = 'registeredAt';
        if (!in_array(strtoupper($sortDir), ['ASC', 'DESC'])) $sortDir = 'DESC';

        return $this->createQueryBuilder('r')
            ->where('r.summit = :summit')
            ->setParameter('summit', $summitId)
            ->orderBy('r.' . $sortField, $sortDir)
            ->getQuery()
            ->getResult();
    }
}