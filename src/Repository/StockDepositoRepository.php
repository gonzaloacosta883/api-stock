<?php

namespace App\Repository;

use App\Entity\StockDeposito;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StockDeposito|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockDeposito|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockDeposito[]    findAll()
 * @method StockDeposito[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockDepositoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockDeposito::class);
    }

    public function noSeComoLlamarlo(int $codigo = null, int $stock = null)
    {
        $queryBuilder = $this->createQueryBuilder('stockDeposito')
        ->leftJoin('App\Entity\Producto', 'producto', 'WITH', 'stockDeposito.producto = producto.id')
        ;
        
        if ($codigo != NULL) {
            $queryBuilder->andWhere('producto.codigo = :codigo')
            ->setParameter('codigo', $codigo);
        }

        if ($stock != NULL) {
            $queryBuilder->andWhere('stockDeposito.cantidad > :stock')
            ->setParameter('stock', $stock);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function getStock($codigoProducto, $codigoDeposito)
    {
        $queryBuilder = $this->createQueryBuilder('stockDeposito')
        ->leftJoin('App\Entity\Producto', 'producto', 'WITH', 'stockDeposito.producto = producto.id')
        ->leftJoin('App\Entity\Deposito', 'deposito', 'WITH', 'stockDeposito.deposito = deposito.id')
        ->andWhere("deposito.codigo = :codigoDeposito")
        ->andWhere("producto.codigo = :codigoProducto")
        ->setParameter("codigoDeposito", $codigoDeposito)
        ->setParameter("codigoProducto", $codigoProducto)
        ->getQuery()
        ->getOneOrNullResult();

        return $queryBuilder;
    }

    // /**
    //  * @return StockDeposito[] Returns an array of StockDeposito objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StockDeposito
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
