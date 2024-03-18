<?php

namespace App\Repository;

use App\Entity\Employer;
use App\Entity\Rdv;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rdv>
 *
 * @method Rdv|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rdv|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rdv[]    findAll()
 * @method Rdv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RdvRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rdv::class);
    }

    //    /**
    //     * @return Rdv[] Returns an array of Rdv objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Rdv
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByEmployerBetweenDates(Employer $employer): array
    {
        $startDay = (new \DateTime())->modify('+1 day'); // Début de J+1
        $endDay = (new \DateTime())->modify('+31 days'); // Fin de J+31

        return $this->createQueryBuilder('r')
            ->where('r.employer = :employer')
            ->andWhere('r.date_rdv BETWEEN :startDay AND :endDay')
            ->setParameter('employer', $employer)
            ->setParameter('startDay', $startDay)
            ->setParameter('endDay', $endDay)
            ->getQuery()
            ->getResult();
    }
}
