<?php
use App\Presentation\Repositories\IncapacidadRepository;
use App\Presentation\Middleware\AuthMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {

    $app->group('/api/incapacidades', function (RouteCollectorProxy $group) {
        
        $group->get('', [IncapacidadRepository::class, 'list']);

        $group->get('/{id}', [IncapacidadRepository::class, 'detail']);
        
        $group->post('', [IncapacidadRepository::class, 'create']);
        
        $group->put('/{id}', [IncapacidadRepository::class, 'update']);

        $group->patch('/{id}/finalizar', [IncapacidadRepository::class, 'finalizar']);

    })->add(new AuthMiddleware());
};