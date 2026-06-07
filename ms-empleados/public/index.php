<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Config/Database.php';

$routes = require __DIR__ . '/../app/Routes/empleados.routes.php';

$app = AppFactory::create();

$routes($app);

$app->run();