<?php

namespace App\Repository;

use App\Entity\Trajet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trajet>
 */
class TrajetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trajet::class);
    }

    /**
     * @param string $depart
     * @param string $arrivee
     * @return Trajet[]
     */
    public function findByDepartAndArrivee(string $depart, string $arrivee): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.depart = :depart')
            ->andWhere('t.arrivee = :arrivee')
            ->andWhere('t.active = :active')
            ->setParameter('depart', $depart)
            ->setParameter('arrivee', $arrivee)
            ->setParameter('active', true)
            ->getQuery()
            ->getResult();
    }
}