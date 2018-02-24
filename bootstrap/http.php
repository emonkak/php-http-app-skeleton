<?php

require __DIR__ . '/../vendor/autoload.php';

(new Dotenv\Dotenv(__DIR__ . '/../'))->load();

if (getenv('APP_DEBUG')) {
    Symfony\Component\Debug\Debug::enable();
}

$app = new App\Adapter\Http\Application(
    require(__DIR__ . '/container.php')
);

$app->pipe(
    (new Middlewares\MethodOverride())
        ->postMethods(['PATCH', 'PUT', 'DELETE'])
        ->parsedBodyParameter('_method')
);

$app->register(App\Adapter\Http\Middlewares\SessionStarter::class);

$app->register(App\Adapter\Http\Middlewares\Authenticator::class);

$app->register(App\Adapter\Http\Middlewares\ViewSharedVariables::class);

$app->registerDispatcher(require(__DIR__ . '/router.php'));

$app->registerErrorHandler();

if (!getenv('APP_DEBUG')) {
    $app->registerOnError(App\Adapter\Http\Middlewares\ErrorPage::class);
}

return $app;
