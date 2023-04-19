<?php

namespace App\Controller;

use App\Entity\Deposito;
use App\Repository\DepositoRepository;
use App\Repository\StockDepositoRepository;
use Doctrine\Persistence\ManagerRegistry;

#Nelmio\ApiDocBundle
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @Route("/api/1.0/deposito")
 */
class DepositoController extends AbstractController
{
    private $depositoRepository;
    private $stockDepositoRepository;
    private $em;

    public function __construct(
        DepositoRepository $depositoRepository,
        StockDepositoRepository $stockDepositoRepository,
        ManagerRegistry $doctrine
    ) {
        $this->depositoRepository = $depositoRepository;
        $this->stockDepositoRepository = $stockDepositoRepository;
        $this->em = $doctrine->getManager();
    }

    /**
     * @Route("/add", name="add_deposito", methods="POST")
     */
    public function addDeposito(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $deposito = $this->depositoRepository->findOneBy(['codigo' => $data["codigo"]]);
            if ($deposito != null) {
                return new JsonResponse([
                    'success' => true,
                    'message' => "Found",
                    'data' => 302,
                ]);
            }

            $nombre = ucfirst(strtolower(trim($data['nombre'])));
            $direccion = ucfirst(strtolower(trim($data['direccion'])));

            $deposito = new Deposito();
            $deposito->setCodigo($data["codigo"]);
            $deposito->setNombre($nombre);
            $deposito->setDireccion($direccion);
            $this->em->persist($deposito);
            $this->em->flush();

            return new JsonResponse([
                'success' => true,
                'message' => 'Created',
                'data' => 201,
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
     * @Route("/all", name="get_depositos", methods="GET")
     */
    public function getDepositos()
    {
        try {
            $depositos = $this->depositoRepository->findAll();
            $arregloDepositos = [];

            foreach ($depositos as $deposito) {
                $arregloDepositos[] = $deposito->__toArray();
            }

            return new JsonResponse([
                "success" => true,
                "message" => "OK",
                "data" => $arregloDepositos,
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
     * @Route("/{codigo}",
     * name="get_deposito_por_codigo",
     * methods="GET",
     * requirements={"codigo"="\d+"},
     * defaults={"codigo": NULL}
     * )
     */
    public function getDepositoPorId($codigo)
    {
        try {
            $deposito = $this->depositoRepository->findOneBy(["codigo" => $codigo]);
            if ($deposito == null) {
                return new JsonResponse([
                    'success' => false,
                    'message' => "Not Found",
                    'data' => 404,
                ]);
            }

            return new JsonResponse([
                'success' => true,
                'message' => "OK",
                'data' => $deposito->__toArray(),
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
     * @Route("/{codigo}/productos",
     * name="get_productos_por_deposito",
     * methods="GET",
     * requirements={"codigo"="\d+"},
     * defaults={"codigo": NULL}
     * )
     */
    public function getProductosPorDeposito($codigo, Request $request): JsonResponse
    {
        try {
            $deposito = $this->depositoRepository
                ->findOneBy(["codigo" => $codigo]);

            if ($deposito == null) {
                return new JsonResponse([
                    'success' => true,
                    'message' => "Not Found",
                    'data' => 404,
                ]);
            }

            $arrayProductosEnDeposito = [];
            $stocks = $deposito->getStocks();
            foreach ($stocks as $stock) {
                $arrayProductosEnDeposito[] = [
                    "producto" => $stock->getProducto()->__toArray(),
                    "unidades" => $stock->getCantidad(),
                ];
            }

            $data = ["deposito" => $deposito->__toArray()];
            $data["deposito"]["productos"] = $arrayProductosEnDeposito;

            return new JsonResponse([
                "success" => true,
                "message" => "OK",
                "deposito" => $data,
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
     * @Route("/edit", name="deposito_edit", methods="PUT")
     */
    public function edit(Request $request): JsonResponse
    {

        try {
            $data = json_decode($request->getContent(), true);
            $deposito = $this->depositoRepository
                ->findOneBy(["codigo" => $data["codigo"]]);

            if ($deposito == null) {
                return new JsonResponse([
                    'success' => true,
                    'message' => "Not Found",
                    'data' => 404,
                ]);
            }

            $deposito->setCodigo($data["codigo"]);
            $deposito->setNombre(strtoupper(trim($data['nombre'])));
            $deposito->setDireccion(strtoupper(trim($data['direccion'])));

            $this->em->persist($deposito);
            $this->em->flush();

            return new JsonResponse([
                'success' => true,
                'message' => "Edited",
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
     * @Route("/{codigo}/delete",
     * name="deposito_delete",
     * methods="DELETE",
     * requirements={"codigo"="\d+"},
     * defaults={"codigo": NULL})
     */
    public function deleteDeposito($codigo): JsonResponse
    {

        try {
            $deposito = $this->depositoRepository
                ->findOneBy(["codigo" => $codigo]);

            if ($deposito == null) {
                return new JsonResponse([
                    'success' => true,
                    'message' => "Not Found",
                    'data' => 404,
                ]);
            }

            if (count($this->stockDepositoRepository->noSeComoLlamarlo($codigo, 0)) != 0) {
                return new JsonResponse([
                    'success' => true,
                    'message' => "No puede eliminar un deposito con stock existente.",
                    'data' => 500,
                ]);
            }

            $this->em->remove($deposito);
            $this->em->flush();

            return new JsonResponse([
                'success' => true,
                'message' => "Deleted",
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
