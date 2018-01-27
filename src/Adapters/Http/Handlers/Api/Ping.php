<?php

namespace App\Adapters\Http\Handlers\Api;

use App\Supports\Respondable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Ping implements RequestHandlerInterface
{
    use Respondable;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->json(['ack' => microtime(true)]);
    }
}
