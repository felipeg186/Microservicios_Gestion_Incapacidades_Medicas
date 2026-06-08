<?php
use App\Repositories\SeguimientoRepository;
use App\Presentation\Middleware\AuthMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {

    $app->group('/api/seguimientos', function (RouteCollectorProxy $group) {
        
        $group->get('', [SeguimientoRepository::class, 'list']);

        $group->get('/historial/{incapacidadId}', [SeguimientoRepository::class, 'historial']);

        $group->get('/{id}', [SeguimientoRepository::class, 'detail']);

        $group->post('', [SeguimientoRepository::class, 'create']);

    })->add(new AuthMiddleware());
};