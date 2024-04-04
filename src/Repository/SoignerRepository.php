<?php

namespace App\Repository;

use App\Entity\Soigner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Soigner>
 *
 * @method Soigner|null find($id, $lockMode = null, $lockVersion = null)
 * @method Soigner|null findOneBy(array $criteria, array $orderBy = null)
 * @method Soigner[]    findAll()
 * @method Soigner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoignerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Soigner::class);
    }

    //    /**
    //     * @return Soigner[] Returns an array of Soigner objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Soigner
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
