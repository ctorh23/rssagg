<?php

declare(strict_types=1);

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

use RssAgg\Framework\Kernel;
use DI\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;

use function FastRoute\simpleDispatcher;

return static function () {
    $containerBuilder = new ContainerBuilder();
    $containerBuilder->addDefinitions(APP_ROOT . '/config/phpdi.php');
    $containerBuilder->useAutowiring(true);
    $containerBuilder->useAttributes(false);
    $container = $containerBuilder->build();

    $routeDispatcher = simpleDispatcher(require APP_ROOT . '/config/routes.php');

    $request = Request::createFromGlobals();

    new Kernel($container, $routeDispatcher)
        ->handleRequest($request)
        ->send();
};
