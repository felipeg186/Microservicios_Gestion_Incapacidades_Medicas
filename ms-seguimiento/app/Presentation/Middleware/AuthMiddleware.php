<?php
namespace App\Presentation\Middleware;

use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $token = $request->getHeaderLine('Authorization');

        if (empty($token)) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode([
                'status'  => 'error',
                'mensaje' => 'Token requerido'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }

        $usuario = Capsule::connection('auth')->table('usuarios')
            ->where('token', $token)
            ->where('sesion_activa', true)
            ->where('estado', 'activo')
            ->first();

        if (empty($usuario)) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode([
                'status'  => 'error',
                'mensaje' => 'Sesión inválida o expirada'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }

        $request = $request->withAttribute('usuario', $usuario);
        return $handler->handle($request);
    }
}