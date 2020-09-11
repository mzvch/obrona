<?php
declare (strict_types = 1);

namespace App\Repository;

use App\Entity\Pocket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pocket | null find ($id, $lockMode = null, $lockVersion = null)
 * @method Pocket | null findOneBy (array $criteria, array $orderBy = null)
 * @method Pocket []    findAll ()
 * @method Pocket []    findBy (array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PocketRepository extends ServiceEntityRepository
{
    public function __construct (ManagerRegistry $registry)
    {
        parent::__construct ($registry, Pocket::class);
    }

    // /**
    //  * @return Pocket[] Returns an array of Pocket objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Pocket
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}