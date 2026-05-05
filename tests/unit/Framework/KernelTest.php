<?php

declare(strict_types=1);

namespace RssAggTests\Unit\Framework;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DI\Container;
use RssAgg\Framework\Kernel;

use function FastRoute\simpleDispatcher;
use function dirname;

#[CoversClass(Kernel::class)]
final class KernelTest extends TestCase
{
    public function testHandleRequestNotFound(): void
    {
        $response = $this->simulateBadRequest('GET', '/not/existing');

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Not Found', $response->getContent());
    }

    public function testHandleRequestMethodNotAllowed(): void
    {
        $response = $this->simulateBadRequest('PATCH', '/feed/list');

        $this->assertEquals(405, $response->getStatusCode());
        $this->assertEquals('Method Not Allowed', $response->getContent());
        $this->assertTrue($response->headers->has('Allow'));
    }

    public function testHandleRequestFound(): void
    {
        $response = $this->simulateSuccessfullRequest('GET', '/feed/manage/1', 'RssAgg\\Controller\\FeedController', 'edit', ['id' => 1]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    private function simulateBadRequest(string $method, string $uri): Response
    {
        $container = $this->createStub(Container::class);

        $routeDispatcher = simpleDispatcher(require dirname(__DIR__, 3) . '/config/routes.php');

        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('getMethod')
            ->willReturn($method);
        $request->expects($this->once())
            ->method('getPathInfo')
            ->willReturn($uri);

        $kernel = new Kernel($container, $routeDispatcher);
        return $kernel->handleRequest($request);
    }

    private function simulateSuccessfullRequest(string $method, string $uri, string $ctrlClass, string $ctrlMethod, array $ctrlArgs): Response
    {
        $container = $this->createMock(Container::class);
        $container->expects($this->once())
            ->method('call')
            ->with([$ctrlClass, $ctrlMethod], $ctrlArgs)
            ->willReturn(new Response()->setStatusCode(200));

        $routeDispatcher = simpleDispatcher(require dirname(__DIR__, 3) . '/config/routes.php');

        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('getMethod')
            ->willReturn($method);
        $request->expects($this->once())
            ->method('getPathInfo')
            ->willReturn($uri);

        $kernel = new Kernel($container, $routeDispatcher);
        return $kernel->handleRequest($request);
    }
}
