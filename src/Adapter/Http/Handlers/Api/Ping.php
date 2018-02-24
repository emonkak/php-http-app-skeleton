<?php

namespace App\Adapter\Http\Handlers\Api;

use App\Support\Respondable;
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
