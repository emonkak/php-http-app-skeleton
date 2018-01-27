<?php

namespace App\Adapters\Http\Handlers\Api;

use App\Supports\Respondable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Hello implements RequestHandlerInterface
{
    use Respondable;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->json(['name' => $request->getAttribute('name')]);
    }
}
