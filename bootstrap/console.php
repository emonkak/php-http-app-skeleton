<?php

require __DIR__ . '/../vendor/autoload.php';

(new Dotenv\Dotenv(__DIR__ . '/../'))->load();

$app = new App\Adapters\Console\Application(
    require(__DIR__ . '/container.php')
);

$app->registerCommand(App\Adapters\Console\Command\AccountsCommand::class);

$app->registerErrorHandler();

return $app;
