<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Config/Database.php';

$cors = require __DIR__ . '/../app/Presentation/Middleware/CorsMiddleware.php';
$routes = require __DIR__ . '/../app/Presentation/Routes/seguimientos.routes.php';

$app = AppFactory::create();
$cors($app);
$routes($app);

$app->run();