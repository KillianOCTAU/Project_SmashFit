<?php

namespace App\Repository;

use App\Entity\Recommandation;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RecommandationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recommandation::class);
    }

    public function findByUtilisateurOrderedByScore(Utilisateur $user): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.materiel', 'm')->addSelect('m')
            ->leftJoin('m.marque', 'ma')->addSelect('ma')
            ->andWhere('r.utilisateur = :user')
            ->setParameter('user', $user)
            ->orderBy('r.score', 'DESC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }

    public function deleteForUser(Utilisateur $user): void
    {
        $this->createQueryBuilder('r')
            ->delete()
            ->andWhere('r.utilisateur = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();
    }
}