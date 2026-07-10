<?php

namespace App\Repository;

use App\Entity\Materiel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MaterielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Materiel::class);
    }

    public function findWithFilters(array $c): array
    {
        $qb = $this->createQueryBuilder('m')
            ->leftJoin('m.marque', 'ma')
            ->addSelect('ma')
            ->andWhere('m.disponible = true');

        if (!empty($c['type']))
            $qb->andWhere('m.type = :type')
               ->setParameter('type', $c['type']);

        if (!empty($c['niveau']))
            $qb->andWhere('m.niveauRecommande = :n OR m.niveauRecommande = :tous')
               ->setParameter('n', $c['niveau'])
               ->setParameter('tous', 'tous');

        if (!empty($c['flexibilite']))
            $qb->andWhere('m.flexibilite = :f')
               ->setParameter('f', $c['flexibilite']);

        if (!empty($c['equilibre']))
            $qb->andWhere('m.equilibre = :e')
               ->setParameter('e', $c['equilibre']);

        if (!empty($c['marque']))
            $qb->andWhere('ma.id = :mid')
               ->setParameter('mid', $c['marque']);

        if (!empty($c['search']))
            $qb->andWhere('m.nom LIKE :s OR m.description LIKE :s')
               ->setParameter('s', '%'.$c['search'].'%');

        return $qb->orderBy('m.nom', 'ASC')->getQuery()->getResult();
    }

    public function findAllAvailable(): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.disponible = true')
            ->leftJoin('m.marque', 'ma')->addSelect('ma')
            ->orderBy('m.type', 'ASC')->addOrderBy('m.nom', 'ASC')
            ->getQuery()->getResult();
    }

    public function countByType(): array
    {
        return $this->createQueryBuilder('m')
            ->select('m.type, COUNT(m.id) as total')
            ->andWhere('m.disponible = true')
            ->groupBy('m.type')
            ->getQuery()->getResult();
    }
}