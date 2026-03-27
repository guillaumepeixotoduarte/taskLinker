<?php

namespace App\Repository;

use App\Entity\Employe;
use App\Entity\Projet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Projet>
 */
class ProjetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Projet::class);
    }

    public function findAllActiveForUser(Employe $user): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.archive = :archived')
            ->setParameter('archived', false);

        // Si l'utilisateur n'est PAS un chef de projet, on restreint aux projets où il est inscrit
        if (!in_array('ROLE_CHEF_PROJET', $user->getRoles())) {
            $qb->innerJoin('p.employes', 'e')
            ->andWhere('e.id = :userId')
            ->setParameter('userId', $user->getId());
        }

        // On peut ajouter un tri par date de création par exemple
        $qb->orderBy('p.id', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function isUserLinkedToProjet(int $projetId, int $userId): bool
    {
        $result = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->innerJoin('p.employes', 'e')
            ->where('p.id = :projetId')
            ->andWhere('e.id = :userId')
            ->setParameter('projetId', $projetId)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleScalarResult();

        return $result > 0;
    }

    //    /**
    //     * @return Projet[] Returns an array of Projet objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Projet
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
