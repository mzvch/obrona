<?php
declare (strict_types = 1);

namespace App\Repository;

use App\Entity\FinancialTransaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FinancialTransaction | null find ($id, $lockMode = null, $lockVersion = null)
 * @method FinancialTransaction | null findOneBy (array $criteria, array $orderBy = null)
 * @method FinancialTransaction []    findAll ()
 * @method FinancialTransaction []    findBy (array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FinancialTransactionRepository extends ServiceEntityRepository
{
    public function __construct (ManagerRegistry $registry)
    {
        parent::__construct ($registry, FinancialTransaction::class);
    }

    // /**
    //  * @return FinancialTransaction[] Returns an array of FinancialTransaction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FinancialTransaction
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAllAssignedToUser (int $userId, int $page, array $filters)
    {
        $qb = $this -> createQueryBuilder ('t')
            -> join ('t.pocket', 'p')
            -> join ('p.user', 'u')
            -> where ('u.id = :userId')
            -> setParameter ('userId', $userId);

        $qb = $this -> applyFilters ($qb, $filters);

        return $qb -> setMaxResults (3)
            -> setFirstResult ($page * 3 - 3)
            -> orderBy ('t.id', 'DESC')
            -> getQuery ()
            -> execute ();
    }

    public function countTransactions (int $userId, array $filters)
    {
        $qb = $this -> createQueryBuilder ('t')
            -> select ('count(t.id)')
            -> join ('t.pocket', 'p')
            -> join ('p.user', 'u')
            -> where ('u.id = :userId')
            -> setParameter ('userId', $userId);

        $qb = $this -> applyFilters ($qb, $filters);

        return $qb -> getQuery ()
            -> getSingleScalarResult ();
    }

    private function applyFilters (QueryBuilder $qb, array $filters) : QueryBuilder
    {
        foreach ($filters as $filter => $value)
        {
            if (empty ($value))
            {
                continue;
            }

            switch ($filter)
            {
                case 'amount_from':
                    $qb -> andWhere ('t.amount >= ' . (float) $value);
                    break;

                case 'amount_to':
                    $qb -> andWhere ('t.amount <= ' . (float) $value);
                    break;

                case 'date_from':
                    $qb -> andWhere ("t.transactionDate >= '$value 00:00:00'");
                    break;

                case 'date_to':
                    $qb -> andWhere ("t.transactionDate <= '$value 23:59:59'");
                    break;

                case 'transaction_type':
                    if ($value == 3)
                    {
                        $qb -> andWhere ('t.amount > 0 OR t.amount < 0');
                    }
                    else
                    {
                        if ($value == 1)
                        {
                            $qb -> andWhere ('t.amount > 0');
                        }
                        else
                        {
                            $qb -> andWhere ('t.amount < 0');
                        }
                    }
                    break;

                case 'pocket':
                    if (is_array ($value))
                    {
                        $qb -> andWhere ('t.pocket IN ('. implode (',', array_keys ($value)) .')') ;
                    }
            }
        }

        return $qb;
    }
}