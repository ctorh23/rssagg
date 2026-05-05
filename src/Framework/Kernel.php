<?php

declare(strict_types=1);

namespace RssAgg\Framework;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DI\Container;
use FastRoute\Dispatcher;
use RssAgg\Framework\Exceptions\RouteException;
use stdClass;

use function count;
use function array_map;
use function array_keys;
use function array_values;

final class Kernel
{
    public function __construct(
        private Container $container,
        private Dispatcher $routeDispatcher,
    ) {
        //
    }

    public function handleRequest(Request $request): Response
    {
        try {
            $controller = $this->resolveController($request->getMethod(), $request->getPathInfo());
        } catch (RouteException $e) {
            $response = new Response($e->getMessage(), $e->getCode());
            $headers = $e->allHeaders() ?? [];
            if (count($headers)) {
                array_map([$response->headers, 'set'], array_keys($headers), array_values($headers));
            }
            return $response;
        }

        return $this->container->call([$controller->class, $controller->method], $controller->args);
    }

    private function resolveController(string $method, string $uri): stdClass
    {
        $routeInfo = $this->routeDispatcher->dispatch($method, $uri);
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw RouteException::notFound();
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw RouteException::methodNotAllowed();
            case Dispatcher::FOUND:
                $controller = new stdClass();
                $controller->class = "RssAgg\\Controller\\{$routeInfo[1][0]}Controller";
                $controller->method = $routeInfo[1][1];
                $controller->args = $routeInfo[2];
                return $controller;
            default:
                throw RouteException::unknownError();
        }
    }
}
