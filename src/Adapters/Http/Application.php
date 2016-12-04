<?php

namespace App\Adapters\Http;

use Emonkak\HttpMiddleware\Application as BaseApplication;
use Emonkak\HttpMiddleware\Dispatcher;
use Emonkak\HttpMiddleware\ErrorLogger;
use Emonkak\Router\RouterInterface;
use Interop\Container\ContainerInterface;
use Monolog\ErrorHandler;
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

    /**
     * @param string $baseDir
     */
    public function __construct($baseDir)
    {
        parent::__construct();

        $this->baseDir = $baseDir;
        $this->container = $this->prepareContainer();
    }

    /**
     * Creates a path from the application root.
     *
     * @param string|null $path
     * @return string
     */
    public function path($path = null)
    {
        return $this->baseDir . ($path != '' ? '/' . $path : $path);
    }

    /**
     * @param string $middleware
     * @return $this
     */
    public function register($middleware)
    {
        return $this->pipe($this->container->get($middleware));
    }

    /**
     * @param string   $middleware
     * @param callable $predicate
     * @return $this
     */
    public function registerIf($middleware, callable $predicate)
    {
        return $this->pipeIf($this->container->get($middleware), $predicate);
    }

    /**
     * @param string $middleware
     * @param string $path
     * @return $this
     */
    public function registerOn($middleware, $path)
    {
        return $this->pipeOn($this->container->get($middleware), $path);
    }

    /**
     * @param strin $errorMiddleware
     * @return $this
     */
    public function registerOnError($errorMiddleware)
    {
        return $this->pipeOnError($this->container->get($errorMiddleware));
    }

    /**
     * @return $this
     */
    public function registerDispatcher()
    {
        $router = $this->prepareRouter();

        return $this->pipe(new Dispatcher($router, $this->container));
    }

    /**
     * @return $this
     */
    public function registerErrorHandler()
    {
        $logger = $this->container->get(LoggerInterface::class);

        $errorHandler = new ErrorHandler($logger);
        $errorHandler->registerErrorHandler();
        $errorHandler->registerFatalHandler();

        return $this->pipeOnError(new ErrorLogger($logger));
    }

    /**
     * @return ContainerInterface
     */
    protected function prepareContainer()
    {
        return require $this->path('bootstrap/container.php');
    }

    /**
     * @return RouterInterface
     */
    protected function prepareRouter()
    {
        return require $this->path('bootstrap/router.php');
    }
}
