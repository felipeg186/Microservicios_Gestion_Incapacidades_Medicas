<?php
namespace App\Presentation\Repositories;

use App\Controllers\IncapacidadController;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IncapacidadRepository
{
    function list(Request $request, Response $response)
    {
        try {
            $filtros = $request->getQueryParams();
            $controller = new IncapacidadController();
            $incapacidades = $controller->getIncapacidades($filtros);
            $response->getBody()->write($incapacidades->toJson());
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
            $controller = new IncapacidadController();
            $incapacidad = $controller->getIncapacidad($args['id']);
            $response->getBody()->write($incapacidad->toJson());
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
            $controller = new IncapacidadController();
            $incapacidad = $controller->guardarIncapacidad($data);
            $response->getBody()->write($incapacidad->toJson());
            return $response
                ->withStatus(201)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $code = match($ex->getCode()) {
                1 => 406,
                4, 5 => 409,
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

    function update(Request $request, Response $response, $args)
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);
            $controller = new IncapacidadController();
            $incapacidad = $controller->modificarIncapacidad($args['id'], $data);
            $response->getBody()->write($incapacidad->toJson());
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $code = $ex->getCode() == 2 ? 404 : 400;
            $response->getBody()->write(json_encode([
                'status'  => 'error',
                'mensaje' => $ex->getMessage()
            ]));
            return $response
                ->withStatus($code)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    function finalizar(Request $request, Response $response, $args)
    {
        try {
            $controller = new IncapacidadController();
            $incapacidad = $controller->finalizarIncapacidad($args['id']);
            $response->getBody()->write($incapacidad->toJson());
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $code = $ex->getCode() == 2 ? 404 : 400;
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