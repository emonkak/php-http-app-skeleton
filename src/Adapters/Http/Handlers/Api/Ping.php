<?php

namespace App\Adapters\Http\Handlers\Api;

use App\Supports\Respondable;
use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;

class Ping implements ServerMiddlewareInterface
{
    use Respondable;

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        return $this->json(['ack' => microtime(true)]);
    }
}
