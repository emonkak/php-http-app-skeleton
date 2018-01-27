<?php

namespace App\Adapters\Console;

use Monolog\ErrorHandler;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;

class Application extends BaseApplication
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(string $baseDir, string $name = 'UNKNOWN', string $version = 'UNKNOWN')
    {
        parent::__construct($name, $version);

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

    public function registerCommand(string $command): Command
    {
        return $this->add($this->container->get($command));
    }

    public function registerErrorHandler(): Application
    {
        $logger = $this->container->get(LoggerInterface::class);

        ErrorHandler::register($logger);

        return $this;
    }

    protected function prepareContainer(): ContainerInterface
    {
        return require $this->path('bootstrap/container.php');
    }
}
