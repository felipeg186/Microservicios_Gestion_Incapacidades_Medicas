<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Config/Database.php';

$cors = require __DIR__ . '/../app/Presentation/Middleware/CorsMiddleware.php';
$routes = require __DIR__ . '/../app/Routes/auth.routes.php';

$app = AppFactory::create();

$routes($app);

$app->run();