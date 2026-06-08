<?php
use App\Presentation\Repositories\AuthRepository;
use App\Presentation\Middleware\AuthMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {

    $app->post('/api/auth/login', [AuthRepository::class, 'login']);

    $app->group('/api/auth', function (RouteCollectorProxy $group) {
        $group->post('/logout', [AuthRepository::class, 'logout']);
        $group->get('/validar', [AuthRepository::class, 'validarSesion']);
    })->add(new AuthMiddleware());
};