<?php

use App\Adapters\Http\Handlers;
use Emonkak\Router\TrieRouterBuilder;

return (new TrieRouterBuilder())
    ->get('/', Handlers\Index::class)

    ->get('/api/ping', Handlers\Api\Ping::class)

    ->get('/accounts/sign_up', Handlers\Accounts\SignUp::class)
    ->post('/accounts', Handlers\Accounts\Create::class)

    ->get('/sessions/login', Handlers\Sessions\Login::class)
    ->post('/sessions', Handlers\Sessions\Create::class)
    ->delete('/sessions', Handlers\Sessions\Delete::class)

    ->build();
