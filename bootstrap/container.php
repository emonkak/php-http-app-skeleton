<?php

use App\Adapter\Database\MasterConnection;
use App\Adapter\Database\SlaveConnection;
use Emonkak\Database\MasterSlaveConnection;
use Emonkak\Database\PDO;
use Emonkak\Database\PDOConnector;
use Emonkak\Database\PDOInterface;
use Emonkak\Database\PDOTransactionInterface;
use Emonkak\Di\Container;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\Scope\SingletonScope;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger as Monolog;
use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Xiaoler\Blade\Compilers\BladeCompiler;
use Xiaoler\Blade\Engines\CompilerEngine;
use Xiaoler\Blade\Engines\EngineResolver;
use Xiaoler\Blade\Factory as BladeFactory;
use Xiaoler\Blade\FileViewFinder;
use Xiaoler\Blade\Filesystem;

$container = new Container(
    new DefaultInjectionPolicy(),
    new ArrayObject()
);

$container->set(
    LoggerInterface::class,
    (new Monolog('app'))
        ->pushHandler(
            new RotatingFileHandler(__DIR__ . '/../storage/logs/' .  PHP_SAPI . '.log'),
            LogLevel::DEBUG
        )
);

$container->factory(SessionInterface::class, function() {
    $storage = new NativeSessionStorage(
        [],
        new NativeFileSessionHandler(__DIR__ . '/../storage/sessions')
    );
    return new Session($storage);
})->in(SingletonScope::getInstance());

$container->factory(BladeFactory::class, function() {
    $fileSystem = new Filesystem();
    $resolver = new EngineResolver();
    $resolver->register('blade', function () use ($fileSystem) {
        $compiler = new BladeCompiler(
            $fileSystem,
            __DIR__ . '/../storage/templates'
        );
        return new CompilerEngine($compiler);
    });
    $finder = new FileViewFinder($fileSystem, [__DIR__ . '/../resources/templates']);
    return new BladeFactory($resolver, $finder);
})->in(SingletonScope::getInstance());

$container->factory(MasterConnection::class, function() {
    return new MasterConnection(
        sprintf(
            '%s:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            getenv('DB_MASTER_CONNECTION'),
            getenv('DB_MASTER_HOST'),
            getenv('DB_MASTER_PORT'),
            getenv('DB_MASTER_DATABASE')
        ),
        getenv('DB_MASTER_USERNAME'),
        getenv('DB_MASTER_PASSWORD'),
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]
    );
})->in(SingletonScope::getInstance());

$container->factory(SlaveConnection::class, function() {
    return new SlaveConnection(
        sprintf(
            '%s:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            getenv('DB_SLAVE_CONNECTION'),
            getenv('DB_SLAVE_HOST'),
            getenv('DB_SLAVE_PORT'),
            getenv('DB_SLAVE_DATABASE')
        ),
        getenv('DB_SLAVE_USERNAME'),
        getenv('DB_SLAVE_PASSWORD'),
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]
    );
})->in(SingletonScope::getInstance());

$container->factory(PDOInterface::class, function(MasterConnection $master, SlaveConnection $slave) {
    return new MasterSlaveConnection($master, $slave);
})->in(SingletonScope::getInstance());

$container->alias(PDOTransactionInterface::class, PDOInterface::class);

return $container;
