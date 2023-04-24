<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Repository\CategoriaRepository;
use App\Repository\ProductoRepository;
use App\Repository\StockDepositoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/1.0/producto")
 */
class ProductoController extends AbstractController
{
    private $productoRepository;
    private $categoriaRepository;
    private $stockDepositoRepository;
    private $em;
    private $params;

    public function __construct(
        ProductoRepository $productoRepository,
        CategoriaRepository $categoriaRepository,
        StockDepositoRepository $stockDepositoRepository,
        ManagerRegistry $doctrine,
        ParameterBagInterface $params
    ) {
        $this->productoRepository = $productoRepository;
        $this->categoriaRepository = $categoriaRepository;
        $this->stockDepositoRepository = $stockDepositoRepository;
        $this->em = $doctrine->getManager();
        $this->params = $params;
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

            $nombreFormateado = str_replace('/', "-", $data["nombre"]);
            $nombreFormateado = str_replace(' ', "-", $nombreFormateado);
            $nombreFormateado = trim(strtoupper($nombreFormateado));

            $producto->setNombre($nombreFormateado);
            $producto->setCodigoColor(strtoupper(trim($data['codigoColor'])));
            $producto->setPrecio($data['precio']);
            $producto->setCategoria($categoria);
            $producto->setCodigo($data["codigo"]);
            $producto->setMarca($data["marca"]);

            $path = $this->params->get('archivos_adjuntos_processors_directory') . "/" . $nombreFormateado;
            if (str_contains(php_uname("s"), "Windows") == true) {
                $path = str_replace("/", "'\'", $path);
            }
            $path = str_replace("'", "", $path);
            $producto->setDirectorio($path);

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

            if ($categoria == null) {
                return new JsonResponse([
                    'success' => false,
                    'message' => "Categoria Not found",
                    'data' => 404,
                ]);
            }

            $nombreFormateado = str_replace('/', "-", $data["nombre"]);
            $nombreFormateado = str_replace(' ', "-", $nombreFormateado);
            $nombreFormateado = trim(strtoupper($nombreFormateado));

            
            if($data["codigo"] != $producto->getCodigo())
            {
                $codigo = $this->productoRepository->findOneBy(["codigo" => $data["codigo"]]);
                if($codigo != NULL)
                {
                    return new JsonResponse([
                        'success' => false,
                        'message' => "The code is not available",
                        'data' => 500,
                    ]);
                }
            }
            
            $producto->setCodigo($data["codigo"]);
            $producto->setNombre($nombreFormateado);
            $producto->setCodigoColor(strtoupper(trim($data['codigoColor'])));
            $producto->setPrecio($data['precio']);
            $producto->setCategoria($categoria);
            $producto->setMarca($data["marca"]);

            $path = $this->params->get('archivos_adjuntos_processors_directory') . "/" . $nombreFormateado;
            if (str_contains(php_uname("s"), "Windows") == true) {
                $path = str_replace("/", "'\'", $path);
            }
            $path = str_replace("'", "", $path);
            $producto->setDirectorio($path);

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
