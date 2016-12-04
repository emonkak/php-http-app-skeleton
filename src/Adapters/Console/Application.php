<?php

namespace App\Adapters\Console;

use Monolog\ErrorHandler;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;

class Application extends BaseApplication
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param string $baseDir
     * @param string $name
     * @param string $version
     */
    public function __construct($baseDir, $name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        parent::__construct($name, $version);

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
     * @param string $command
     * @return Command
     */
    public function register($command)
    {
        return $this->add($this->container->get($command));
    }

    /**
     * @param string[] $commands
     * @return $this
     */
    public function registerCommands(array $commands)
    {
        foreach ($commands as $command) {
            $this->add($this->container->get($command));
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function registerErrorHandler()
    {
        $logger = $this->container->get(LoggerInterface::class);

        ErrorHandler::register($logger);

        return $this;
    }

    protected function prepareContainer()
    {
        return require $this->path('bootstrap/container.php');
    }
}
