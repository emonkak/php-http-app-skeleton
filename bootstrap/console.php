<?php

require __DIR__ . '/../vendor/autoload.php';

(new Dotenv\Dotenv(__DIR__ . '/../'))->load();

$app = new App\Adapters\Console\Application(realpath(__DIR__ . '/../'));

$app->registerCommand(App\Adapters\Console\Command\AccountsCommand::class);

$app->registerErrorHandler();

return $app;
