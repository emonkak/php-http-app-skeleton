<?php

namespace App\Adapter\Http\Middlewares;

use App\UseCase\AuthenticationService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Xiaoler\Blade\Factory as ViewFactory;

class ViewSharedVariables implements MiddlewareInterface
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var ViewFactory
     */
    private $viewFactory;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(
        AuthenticationService $authenticationService,
        ViewFactory $viewFactory,
        SessionInterface $session
    ) {
        $this->authenticationService = $authenticationService;
        $this->viewFactory = $viewFactory;
        $this->session = $session;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->viewFactory->share([
            'request' => $request,
            'uri' => $request->getUri(),
            'account' => $this->authenticationService->getAccount(),
            'session' => $this->session,
            'flashes' => $this->session->getFlashBag(),
        ]);

        return $handler->handle($request);
    }
}
