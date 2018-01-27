<?php

namespace App\Adapters\Http\Handlers\Sessions;

use App\Supports\Respondable;
use App\UseCases\AuthenticationService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Delete implements RequestHandlerInterface
{
    use Respondable;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->authenticationService->revoke();

        return $this->redirect('/sessions/login');
    }
}
