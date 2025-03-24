<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BaseApiController extends AbstractController
{
    protected ValidatorInterface $validatorService;

    public function __construct(ValidatorInterface $validatorService)
    {
        $this->validatorService = $validatorService;
    }

    protected function responseJSON($result, $encodeDetail = true)
    {
        $response = [];
        $httpStatusCode = Response::HTTP_OK;

        if ($result instanceof AccessDeniedException) {
            // Manejar específicamente la excepción de acceso denegado
            $httpStatusCode = Response::HTTP_FORBIDDEN; // 403
            $cleanedMessage = trim(str_replace(["The file", "could not be accessed"], "", $result->getMessage()));
            $response['errors'][] = [
                'status' => $httpStatusCode,
                'title' => 'Acceso denegado',
                'detail' => $cleanedMessage,
            ];
        } elseif ($result instanceof NotFoundHttpException) {
            // Manejar la excepción cuando no se encuentra un registro
            $httpStatusCode = Response::HTTP_NOT_FOUND; // 404
            $response['errors'][] = [
                'status' => $httpStatusCode,
                'title' => 'Recurso no encontrado',
                'detail' => $result->getMessage(),
            ];
        } elseif ($result instanceof ConflictHttpException) {
            // Manejar la excepción cuando el recurso ya existe
            $httpStatusCode = Response::HTTP_CONFLICT; // 409
            $response['errors'][] = [
                'status' => $httpStatusCode,
                'title' => 'Conflicto de recurso',
                'detail' => $result->getMessage(),
            ];
        } elseif ($result instanceof \Exception) {
            // Manejar la excepción cuando hay un error interno en el servidor
            $httpStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $response['errors'][] = [
                'status' => $httpStatusCode,
                'title' => $result->getMessage(),
                'detail' => $encodeDetail ? base64_encode($result->getTraceAsString()) : $result->getTraceAsString(),
            ];
        } else {
            // $response['status'] = $httpStatusCode;
            // $response['title'] = "OK";
            $response['data'] = $result;
        }

        return new JsonResponse($response, $httpStatusCode);
    }


    /**
     * @throws InvalidParameters
     */
    protected function validateParameters(array $data, array $parametersValids): void
    {
        $validationIsOk = $this->validatorService->validateParameters($data, $parametersValids);
        if (true !== $validationIsOk) {
            throw new InvalidParameterException($validationIsOk);
        }
    }
}
