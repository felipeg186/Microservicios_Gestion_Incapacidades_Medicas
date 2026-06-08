<?php
use App\Repositories\EmpleadoRepository;
use App\Presentation\Middleware\AuthMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {

    $app->group('/api/empleados', function (RouteCollectorProxy $group) {
        
        $group->get('', [EmpleadoRepository::class, 'list']);

        $group->get('/{id}', [EmpleadoRepository::class, 'detail']);

        
        $group->post('', [EmpleadoRepository::class, 'create']);

        
        $group->put('/{id}', [EmpleadoRepository::class, 'update']);

        
        $group->patch('/{id}/estado', [EmpleadoRepository::class, 'cambiarEstado']);

    })->add(new AuthMiddleware());
};