<?php

require __DIR__ . '/../vendor/autoload.php';

(new Dotenv\Dotenv(__DIR__ . '/../'))->load();

if (getenv('APP_DEBUG')) {
    Symfony\Component\Debug\Debug::enable();
}

$app = new App\Adapters\Http\Application(realpath(__DIR__ . '/../'));

$app->pipe(
    (new Middlewares\MethodOverride())
        ->postMethods(['PATCH', 'PUT', 'DELETE'])
        ->parsedBodyParameter('_method')
);

$app->register(App\Adapters\Http\Middlewares\SessionStarter::class);

$app->register(App\Adapters\Http\Middlewares\Authenticator::class);

$app->register(App\Adapters\Http\Middlewares\ViewSharedVariables::class);

$app->registerDispatcher();

$app->registerErrorHandler();

if (!getenv('APP_DEBUG')) {
    $app->registerOnError(App\Adapters\Http\Middlewares\ErrorPage::class);
}

return $app;
