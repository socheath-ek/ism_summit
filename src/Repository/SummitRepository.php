<?php
namespace App\Repository;

use App\Entity\Summit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SummitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Summit::class);
    }

    public function findActiveSummit(): ?Summit
    {
        return $this->findOneBy(['isActive' => true]);
    }
}