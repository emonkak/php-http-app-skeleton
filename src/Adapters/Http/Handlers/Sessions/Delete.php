<?php

namespace App\Adapters\Http\Handlers\Sessions;

use App\Supports\Respondable;
use App\UseCases\AuthenticationService;
use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;

class Delete implements ServerMiddlewareInterface
{
    use Respondable;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @param AuthenticationService $authenticationService
     */
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $this->authenticationService->revoke();

        return $this->redirect('/sessions/login');
    }
}
