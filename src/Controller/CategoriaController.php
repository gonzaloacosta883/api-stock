<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Entity\Categoria;
use App\Repository\CategoriaRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Annotations as OA;

/**
 * @Route("/api/1.0/categoria")
 */
class CategoriaController extends AbstractController
{
    private $categoriaRepository;
    private $em;

    public function __construct(CategoriaRepository $categoriaRepository, ManagerRegistry $doctrine)
    {
        $this->categoriaRepository = $categoriaRepository;
        $this->em = $doctrine->getManager();
    }

    /**
     * @Route("/add", name="add_categoria", methods="POST")
     */
    public function addCategoria(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $nombreCategoria = strtoupper(trim($data['nombre'])); //Mayuscula sin espacios

            $duplicado = $this->categoriaRepository
                ->findOneBy(['codigo' => $data['codigo']]);

            if ($duplicado != null) {
                return new JsonResponse([
                    'success' => true,
                    'message' => "Found",
                    'data' => 302,
                ]);
            }

            $categoria = new Categoria();
            $categoria->setNombre($nombreCategoria);
            $categoria->setCodigo($data['codigo']);
            $this->em->persist($categoria);
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
     * @Route("/all", name="get_all_categorias", methods="GET")
     */
    public function getCategorias()
    {

        try {
            $categorias = $this->categoriaRepository->findAll();
            $arregloCategorias = [];

            $response = new JsonResponse();
            if (!empty($categorias)) {

                for ($i = 0; $i < count($categorias); $i++) {
                    array_push($arregloCategorias, $categorias[$i]->__toArray());
                }
            }

            return new JsonResponse([
                'success' => true,
                'message' => 'OK',
                'data' => $arregloCategorias,
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
     * name="get_categoria",
     * methods="GET",
     * requirements={"codigo"="\d+"},
     * defaults={"codigo": null}
     * )
     */
    public function getCategoriaPorCodigo($codigo)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $categoria = $this->categoriaRepository
                ->findOneBy(["codigo" => $codigo]);

            if ($categoria == null) {
                return new JsonResponse([
                    'success' => true,
                    'message' => "Not found",
                    'data' => 404,
                ]);
            }

            return new JsonResponse([
                'success' => true,
                'message' => "OK",
                'data' => [
                    "id" => $categoria->getId(),
                    "codigo" => $categoria->getCodigo(),
                    "nombre" => $categoria->getNombre(),
                ],
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
     * @Route("/{codigo}/productos", name="get_productos_por_categoria", methods="GET")
     */
    public function getProductosPorCategoria($codigo): JsonResponse
    {

        $categoria = $this->categoriaRepository->findOneBy(["codigo" => $codigo]);
        if ($categoria == NULL) {
            return new JsonResponse([
                'success' => true,
                'message' => "Not Found",
                'data' => 404,
            ]);
        }

        $arregloProductos = [];
        foreach ($categoria->getProductos() as $producto) {
            $arregloProductos[] = $producto->__toArray();
        }
        
        return new JsonResponse([
            'success' => true,
            'message' => "OK",
            "data" => $arregloProductos
        ]);
    }

    /**
     * @Route("/edit", name="categoria_edit", methods="PUT")
     */
    public function editCategoria($id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $categoria = $this->categoriaRepository
                ->findOneBy(["codigo" => $data["codigo"]]);

            if ($categoria == null) {
                return new JsonResponse([
                    'success' => false,
                    'message' => "Not found",
                    'data' => 404,
                ]);
            }

            $nombreCategoria = strtoupper(trim($data['nombre']));
            $categoria->setNombre($nombreCategoria);
            $categoria->setCodigo($data['codigo']);

            $this->em->persist($categoria);
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
     * @Route("/{codigo}/delete", name="categoria_detele", methods="DELETE")
     */
    public function deleteCategoria($codigo): JsonResponse
    {

        try {
            $categoria = $this->categoriaRepository
                ->findOneBy(["codigo" => $codigo]);

            if ($categoria == null) {
                return new JsonResponse([
                    'success' => true,
                    'message' => "Not found",
                    'data' => 404,
                ]);
            }

            if (count($categoria->getProductos()) > 0) {
                return new JsonResponse([
                    'success' => false,
                    'message' => "Existen productos asociados a la categoria",
                    'data' => 423,
                ]);
            }

            $this->em->remove($categoria);
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
