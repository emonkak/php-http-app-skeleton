<?php

namespace App\Adapters\Http\Middlewares;

use App\UseCases\AuthenticationService;
use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Xiaoler\Blade\Factory as ViewFactory;

class ViewSharedVariables implements ServerMiddlewareInterface
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

    /**
     * @param AuthenticationService $authenticationService
     * @param ViewFactory           $viewFactory
     * @param SessionInterface      $session
     */
    public function __construct(
        AuthenticationService $authenticationService,
        ViewFactory $viewFactory,
        SessionInterface $session)
    {
        $this->authenticationService = $authenticationService;
        $this->viewFactory = $viewFactory;
        $this->session = $session;
    }

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $this->viewFactory->share([
            'request' => $request,
            'uri' => $request->getUri(),
            'account' => $this->authenticationService->getAccount(),
            'session' => $this->session,
            'flashes' => $this->session->getFlashBag(),
        ]);

        return $delegate->process($request);
    }
}
