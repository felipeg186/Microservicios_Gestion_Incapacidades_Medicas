<?php
namespace App\Presentation\Repositories;

use App\Controllers\AuthController;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthRepository
{
    function login(Request $request, Response $response)
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);
            $controller = new AuthController();
            $resultado = $controller->login($data);
            $response->getBody()->write(json_encode([
                'status'  => 'success',
                'mensaje' => 'Inicio de sesión exitoso',
                'data'    => $resultado
            ]));
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $code = $ex->getCode() == 1 ? 406 : 401;
            $response->getBody()->write(json_encode([
                'status'  => 'error',
                'mensaje' => $ex->getMessage()
            ]));
            return $response
                ->withStatus($code)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    function logout(Request $request, Response $response)
    {
        try {
            $token = $request->getHeaderLine('Authorization');
            $controller = new AuthController();
            $controller->logout($token);
            $response->getBody()->write(json_encode([
                'status'  => 'success',
                'mensaje' => 'Sesión cerrada correctamente'
            ]));
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode([
                'status'  => 'error',
                'mensaje' => $ex->getMessage()
            ]));
            return $response
                ->withStatus(401)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    function validarSesion(Request $request, Response $response)
    {
        try {
            $token = $request->getHeaderLine('Authorization');
            $controller = new AuthController();
            $usuario = $controller->validarSesion($token);
            $response->getBody()->write(json_encode([
                'status'  => 'success',
                'mensaje' => 'Sesión válida',
                'data'    => $usuario
            ]));
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode([
                'status'  => 'error',
                'mensaje' => $ex->getMessage()
            ]));
            return $response
                ->withStatus(401)
                ->withHeader('Content-Type', 'application/json');
        }
    }
}