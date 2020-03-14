<?php

namespace App\Repository;

use App\Entity\AdditionalFields;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AdditionalFields|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdditionalFields|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdditionalFields[]    findAll()
 * @method AdditionalFields[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdditionalFieldsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdditionalFields::class);
    }

    // /**
    //  * @return AdditionalFields[] Returns an array of AdditionalFields objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdditionalFields
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
