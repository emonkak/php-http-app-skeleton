<?php

(new Dotenv\Dotenv(__DIR__))->load();

$container = require __DIR__ . '/bootstrap/container.php';

$config = new \ArrayObject();

$config['db'] = $container->get(Emonkak\Database\PDOInterface::class);

$config['phpmig.adapter'] = new Phpmig\Adapter\PDO\Sql($config['db'], 'migrations');

$config['phpmig.migrations_path'] = __DIR__ . '/database/migrations';

return $config;
