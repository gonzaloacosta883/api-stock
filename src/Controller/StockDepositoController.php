<?php

namespace App\Controller;

use App\Entity\Deposito;
use App\Entity\Producto;
use App\Entity\StockDeposito;
use App\Repository\DepositoRepository;
use App\Repository\ProductoRepository;
use App\Repository\StockDepositoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/1.0/stock/deposito")
 */
class StockDepositoController extends AbstractController
{
    private $productoRepository;
    private $depositoRepository;
    private $stockDepositoRepository;
    private $em;

    public function __construct(
        ProductoRepository $productoRepository,
        DepositoRepository $depositoRepository,
        StockDepositoRepository $stockDepositoRepository,
        ManagerRegistry $doctrine
    ) {
        $this->productoRepository = $productoRepository;
        $this->depositoRepository = $depositoRepository;
        $this->stockDepositoRepository = $stockDepositoRepository;
        $this->em = $doctrine->getManager();
    }

    /**
     * @Route("/incrementar", name="stock_deposito_incrementar", methods="POST")
     */
    public function incrementar(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $deposito = $this->depositoRepository
                ->findOneBy(["codigo" => $data['codigoDeposito']]);

            $producto = $this->productoRepository
                ->findOneBy(["codigo" => $data['codigoProducto']]);

            if ($deposito == null) {
                return new JsonResponse([
                    'success' => true,
                    'message' => "Deposito Not Found",
                    'data' => 404,
                ]);
            }

            if ($producto == null) {
                return new JsonResponse([
                    'success' => true,
                    'message' => "Producto Not Found",
                    'data' => 404,
                ]);
            }

            // Devuelve el stock de un producto en un deposito
            $stock = $this->stockDepositoRepository
                ->getStock($data['codigoProducto'], $data['codigoDeposito']);

            // El producto no existe en el deposito
            if ($stock == null) {
                $stock = new StockDeposito();
                $stock->setProducto($producto);
                $stock->setDeposito($deposito);
                $stock->incrementarCantidad($data['cantidad']);
                $this->em->persist($stock);
            } else {
                $stock->incrementarCantidad($data['cantidad']);
                $this->em->persist($stock);
            }

            $this->em->flush();

            return new JsonResponse([
                'success' => true,
                'message' => "OK",
                'data' => 200,
            ]);
        } catch (\Throwable $th) {
            return new JsonResponse([
                'success' => false,
                'message' => $th->getMessage(),
                'data' => 500,
            ]);
        }
    }

    /**
     * @Route("/decrementar", name="stock_deposito_decrementar", methods="PUT")
     */
    public function decrementar(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $deposito = $this->depositoRepository
                ->findOneBy(["codigo" => $data['codigoDeposito']]);

            $producto = $this->productoRepository
                ->findOneBy(["codigo" => $data['codigoProducto']]);

            if ($deposito == null) {
                return new JsonResponse([
                    'success' => true,
                    'message' => "Deposito Not Found",
                    'data' => 404,
                ]);
            }

            if ($producto == null) {
                return new JsonResponse([
                    'success' => true,
                    'message' => "Producto Not Found",
                    'data' => 404,
                ]);
            }

            // Devuelve el stock de un producto en un deposito
            $stock = $this->stockDepositoRepository
                ->getStock($data['codigoProducto'], $data['codigoDeposito']);

            if ($stock == null) {
                return new JsonResponse([
                    'success' => false,
                    'message' => "Stock Inexistente",
                    'data' => 404,
                ]);
            }

            $stock->decrementarCantidad($data["cantidad"]);
            $this->em->persist($stock);
            $this->em->flush();

            return new JsonResponse([
                'success' => true,
                'message' => "OK",
                'data' => 200,
            ]);
        } catch (\Throwable $th) {
            return new JsonResponse([
                'success' => false,
                'message' => $th->getMessage(),
                'data' => 500,
            ]);
        }
    }
}
