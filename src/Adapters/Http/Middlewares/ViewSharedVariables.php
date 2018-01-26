<?php

namespace App\Adapters\Http\Middlewares;

use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Xiaoler\Blade\Factory as ViewFactory;

class ViewSharedVariables implements ServerMiddlewareInterface
{
    /**
     * @var ViewFactory
     */
    private $viewFactory;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @param ViewFactory      $viewFactory
     * @param SessionInterface $session
     */
    public function __construct(ViewFactory $viewFactory, SessionInterface $session)
    {
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
            'account' => $request->getAttribute('_account'),
            'session' => $this->session,
            'flashes' => $this->session->getFlashBag(),
        ]);

        return $delegate->process($request);
    }
}
