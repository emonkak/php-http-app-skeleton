<?php

namespace App\Adapters\Http;

use Emonkak\HttpMiddleware\Application as BaseApplication;
use Emonkak\HttpMiddleware\Dispatcher;
use Emonkak\HttpMiddleware\ErrorLogger;
use Emonkak\Router\RouterInterface;
use Monolog\ErrorHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class Application extends BaseApplication
{
    /**
     * @var string
     */
    private $baseDir;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(string $baseDir)
    {
        $this->baseDir = $baseDir;
        $this->container = $this->prepareContainer();
    }

    /**
     * Creates a path from the application root.
     */
    public function path(string $path = ''): string
    {
        return $this->baseDir . ($path !== '' ? '/' . $path : $path);
    }

    public function register(string $middleware): Application
    {
        return $this->pipe($this->container->get($middleware));
    }

    public function registerIf(string $middleware, callable $predicate): Application
    {
        return $this->pipeIf($this->container->get($middleware), $predicate);
    }

    public function registerOn(string $middleware, string $path): Application
    {
        return $this->pipeOn($this->container->get($middleware), $path);
    }

    public function registerOnError($errorMiddleware): Application
    {
        return $this->pipeOnError($this->container->get($errorMiddleware));
    }

    public function registerDispatcher(): Application
    {
        $router = $this->prepareRouter();

        return $this->pipe(new Dispatcher($router, $this->container));
    }

    public function registerErrorHandler(): Application
    {
        $logger = $this->container->get(LoggerInterface::class);

        $errorHandler = new ErrorHandler($logger);
        $errorHandler->registerErrorHandler();
        $errorHandler->registerFatalHandler();

        return $this->pipeOnError(new ErrorLogger($logger));
    }

    protected function prepareContainer(): ContainerInterface
    {
        return require $this->path('bootstrap/container.php');
    }

    protected function prepareRouter(): RouterInterface
    {
        return require $this->path('bootstrap/router.php');
    }
}
