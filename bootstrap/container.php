<?php

use Emonkak\Database\PDO;
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
use Xiaoler\Blade\Factory as BladeFactory;
use Xiaoler\Blade\FileViewFinder;

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
    $engine = new CompilerEngine(
        new BladeCompiler(__DIR__ . '/../storage/templates')
    );
    $finder = new FileViewFinder([__DIR__ . '/../resources/templates']);
    return new BladeFactory($engine, $finder);
})->in(SingletonScope::getInstance());

$container->factory(PDOInterface::class, function() {
    return new PDO(
        sprintf(
            '%s:host=%s;port=%d;dbname=%s',
            getenv('DB_CONNECTION'),
            getenv('DB_HOST'),
            getenv('DB_PORT'),
            getenv('DB_DATABASE')
        ),
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD'),
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]
    );
})->in(SingletonScope::getInstance());

$container->alias(PDOTransactionInterface::class, PDOInterface::class);

return $container;
