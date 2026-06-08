<?php
namespace App\Presentation\Repositories;

use App\Controllers\SeguimientoController;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SeguimientoRepository
{
    function list(Request $request, Response $response)
    {
        try {
            $params = $request->getQueryParams();
            $incapacidadId = $params['incapacidad_id'] ?? null;
            $controller = new SeguimientoController();
            $seguimientos = $controller->getSeguimientos($incapacidadId);
            $response->getBody()->write($seguimientos->toJson());
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode([
                'status'  => 'error',
                'mensaje' => $ex->getMessage()
            ]));
            return $response
                ->withStatus(500)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    function detail(Request $request, Response $response, $args)
    {
        try {
            $controller = new SeguimientoController();
            $seguimiento = $controller->getSeguimiento($args['id']);
            $response->getBody()->write($seguimiento->toJson());
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $code = $ex->getCode() == 2 ? 404 : 500;
            $response->getBody()->write(json_encode([
                'status'  => 'error',
                'mensaje' => $ex->getMessage()
            ]));
            return $response
                ->withStatus($code)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    function create(Request $request, Response $response)
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);
            $controller = new SeguimientoController();
            $seguimiento = $controller->guardarSeguimiento($data);
            $response->getBody()->write($seguimiento->toJson());
            return $response
                ->withStatus(201)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $code = match($ex->getCode()) {
                1 => 406,
                4 => 409,
                default => 400
            };
            $response->getBody()->write(json_encode([
                'status'  => 'error',
                'mensaje' => $ex->getMessage()
            ]));
            return $response
                ->withStatus($code)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    function historial(Request $request, Response $response, $args)
    {
        try {
            $controller = new SeguimientoController();
            $historial = $controller->getHistorial($args['incapacidadId']);
            $response->getBody()->write($historial->toJson());
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $code = $ex->getCode() == 1 ? 406 : 404;
            $response->getBody()->write(json_encode([
                'status'  => 'error',
                'mensaje' => $ex->getMessage()
            ]));
            return $response
                ->withStatus($code)
                ->withHeader('Content-Type', 'application/json');
        }
    }
}