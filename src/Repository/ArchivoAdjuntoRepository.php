<?php

namespace App\Repository;

use App\Entity\ArchivoAdjunto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ArchivoAdjunto>
 *
 * @method ArchivoAdjunto|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArchivoAdjunto|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArchivoAdjunto[]    findAll()
 * @method ArchivoAdjunto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArchivoAdjuntoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArchivoAdjunto::class);
    }

    public function add(ArchivoAdjunto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ArchivoAdjunto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ArchivoAdjunto[] Returns an array of ArchivoAdjunto objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ArchivoAdjunto
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
