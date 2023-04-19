<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Repository\ProductoRepository;
use App\Repository\CategoriaRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\StockDepositoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/1.0/producto")
 */
class ProductoController extends AbstractController
{
    private $productoRepository;
    private $categoriaRepository;
    private $stockDepositoRepository;
    private $em;

    public function __construct(
        ProductoRepository $productoRepository,
        CategoriaRepository $categoriaRepository,
        StockDepositoRepository $stockDepositoRepository,
        ManagerRegistry $doctrine
        ) 
    {
        $this->productoRepository = $productoRepository;
        $this->categoriaRepository = $categoriaRepository;
        $this->stockDepositoRepository = $stockDepositoRepository;
        $this->em = $doctrine->getManager();
    }

    /**
     * @Route("/add", name="add_producto", methods="POST")
     */
    public function addProducto(Request $request): JsonResponse
    {

        try {
            $data = json_decode($request->getContent(), true);
            $categoria = $this->categoriaRepository
                ->findOneBy(["codigo" => $data["codigoCategoria"]]);

            $producto = $this->productoRepository->findOneBy(['codigo' => $data["codigo"]]);
            if ($producto != null) {
                return new JsonResponse([
                    'success' => false,
                    'message' => "Found",
                    'data' => 302,
                ]);
            }

            $producto = new Producto();
            $producto->setNombre(strtoupper(trim($data['nombre'])));
            $producto->setCodigoColor(strtoupper(trim($data['codigoColor'])));
            $producto->setPrecio($data['precio']);
            $producto->setCategoria($categoria);
            $producto->setCodigo($data["codigo"]);

            $this->em->persist($producto);
            $this->em->flush();

            return new JsonResponse([
                'success' => true,
                'message' => "Created",
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
     * @Route("/all", name="get_productos", methods="GET")
     */
    public function getProductos()
    {

        try {
            $productos = $this->productoRepository->findAll();

            $arregloProductos = [];
            foreach ($productos as $producto) {
                $arregloProductos[] = $producto->__toArray();
            }

            return new JsonResponse([
                'success' => true,
                'message' => "OK",
                'data' => $arregloProductos,
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
     * name="get_producto_por_id",
     * methods="GET",
     * requirements={"codigo"="\d+"},
     * defaults={"codigo": NULL}
     * )
     */
    public function getProductoPorId($codigo)
    {
        try {
            $producto = $this->productoRepository->findOneBy(["codigo" => $codigo]);
            if ($producto == null) {
                return new JsonResponse([
                    'success' => false,
                    'message' => "Not Found",
                    'data' => 404,
                ]);
            }

            return new JsonResponse([
                'success' => true,
                'message' => "OK",
                'data' => $producto->__toArray(),
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
     * @Route("/edit", name="producto_edit", methods="PUT")
     */
    public function edit(Request $request): JsonResponse
    {

        try {
            $data = json_decode($request->getContent(), true);
            $producto = $this->productoRepository->findOneBy(["codigo" => $data["codigo"]]);

            if ($producto == null) {
                return new JsonResponse([
                    'success' => false,
                    'message' => "Not found",
                    'data' => 404,
                ]);
            }

            $categoria = $this->categoriaRepository
                ->findOneBy(["codigo" => $data["codigoCategoria"]]);

            $producto->setCodigo($data['codigo']);
            $producto->setNombre($data['nombre']);
            $producto->setCodigoColor($data['codigoColor']);
            $producto->setPrecio($data['precio']);
            $producto->setCategoria($categoria);

            $this->em->persist($producto);
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
}
