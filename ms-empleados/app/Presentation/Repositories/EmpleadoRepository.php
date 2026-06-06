<?php
namespace App\Repositories;

use App\Controllers\EmpleadoController;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EmpleadoRepository
{
    function list(Request $request, Response $response)
    {
        try {
            $filtros = $request->getQueryParams();
            $controller = new EmpleadoController();
            $empleados = $controller->getEmpleados($filtros);
            $response->getBody()->write($empleados->toJson());
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
            $controller = new EmpleadoController();
            $empleado = $controller->getEmpleado($args['id']);
            $response->getBody()->write($empleado->toJson());
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
            $controller = new EmpleadoController();
            $empleado = $controller->guardarEmpleado($data);
            $response->getBody()->write($empleado->toJson());
            return $response
                ->withStatus(201)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $code = match($ex->getCode()) {
                1 => 406,
                3, 4 => 409,
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
            $controller = new EmpleadoController();
            $empleado = $controller->modificarEmpleado($args['id'], $data);
            $response->getBody()->write($empleado->toJson());
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

    function cambiarEstado(Request $request, Response $response, $args)
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);
            $controller = new EmpleadoController();
            $empleado = $controller->cambiarEstado($args['id'], $data['estado']);
            $response->getBody()->write($empleado->toJson());
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