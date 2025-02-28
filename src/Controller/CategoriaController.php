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
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/api/1.0/categoria")
 */
class CategoriaController extends BaseApiController
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
        $resultService = [];
        try {
            $data = json_decode($request->getContent(), true);
            $nombreCategoria = strtoupper(trim($data['nombre'])); //Mayuscula sin espacios

            $duplicado = $this->categoriaRepository
                ->findOneBy(['codigo' => $data['codigo']]);

            if ($duplicado != null) {
                throw new \Exception('Ya existe una categoria con dicho codigo.', Response::HTTP_CONFLICT);
            }

            $categoria = new Categoria();
            $categoria->setNombre($nombreCategoria);
            $categoria->setCodigo($data['codigo']);
            $this->em->persist($categoria);
            $this->em->flush();

            $resultService = $data;
        } catch (\Exception $e) {
            $resultService = $e;
        }

        return $this->responseJSON($resultService, true);
    }

    /**
     * @Route("/all", name="get_all_categorias", methods="GET")
     */
    public function getCategorias()
    {

        $resultService = [];
        try {
            $categorias = $this->categoriaRepository->findAll();
            $arregloCategorias = [];

            if (!empty($categorias)) {

                for ($i = 0; $i < count($categorias); $i++) {
                    array_push($arregloCategorias, $categorias[$i]->__toArray());
                }
            }
            $resultService = $arregloCategorias;
        } catch (\Exception $e) {
            $resultService = $e;
        }

        return $this->responseJSON($resultService, true);
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

        $resultService = [];
        try {
            $em = $this->getDoctrine()->getManager();
            $categoria = $this->categoriaRepository
                ->findOneBy(["codigo" => $codigo]);

            if (!$categoria) {
                throw new NotFoundHttpException('La categoria no se encuentra.');
            }

            $resultService = [
                "id" => $categoria->getId(),
                "codigo" => $categoria->getCodigo(),
                "nombre" => $categoria->getNombre(),
            ];
        } catch (\Exception $e) {
            $resultService = $e;
        }

        return $this->responseJSON($resultService, true);
    }

    /**
     * @Route("/{codigo}/productos", name="get_productos_por_categoria", methods="GET")
     */
    public function getProductosPorCategoria($codigo): JsonResponse
    {

        $resultService = [];
        try {

            $categoria = $this->categoriaRepository->findOneBy(["codigo" => $codigo]);
            if (!$categoria) {
                throw new NotFoundHttpException('La categoria no se encuentra.');
            }

            $arregloProductos = [];
            foreach ($categoria->getProductos() as $producto) {
                $arregloProductos[] = $producto->__toArray();
            }

            $resultService = $arregloProductos;
        } catch (\Exception $e) {
            $resultService = $e;
        }

        return $this->responseJSON($resultService, true);
    }

    /**
     * @Route("/edit", name="categoria_edit", methods="PUT")
     */
    public function editCategoria(Request $request): JsonResponse
    {

        $resultService = [];
        try {
            $data = json_decode($request->getContent(), true);
            $categoria = $this->categoriaRepository
                ->findOneBy(["codigo" => $data["codigo"]]);

            if (!$categoria) {
                throw new NotFoundHttpException('La categoria no se encuentra.');
            }

            $nombreCategoria = strtoupper(trim($data['nombre']));
            $categoria->setNombre($nombreCategoria);
            $categoria->setCodigo($data['codigo']);

            $this->em->persist($categoria);
            $this->em->flush();

            $resultService = $data;
        } catch (\Exception $e) {
            $resultService = $e;
        }

        return $this->responseJSON($resultService, true);
    }

    /**
     * @Route("/{codigo}/delete", name="categoria_delete", methods="DELETE")
     */
    public function deleteCategoria($codigo): JsonResponse
    {

        $resultService = [];
        try {
            $categoria = $this->categoriaRepository
                ->findOneBy(["codigo" => $codigo]);

            if (!$categoria) {
                throw new NotFoundHttpException('La categoria no se encuentra.');
            }

            if (count($categoria->getProductos()) > 0) {
                throw new AccessDeniedException('Existen productos asociados a la categoría, no se puede eliminar.');
            }

            $this->em->remove($categoria);
            $this->em->flush();

            $resultService = ['title' => 'Recurso no eliminado'];

        } catch (\Exception $e) {
            $resultService = $e;
        }

        return $this->responseJSON($resultService, true);
    }
}
