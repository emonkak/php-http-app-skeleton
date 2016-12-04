<?php

namespace App\Adapters\Http\Middlewares;

use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Xiaoler\Blade\Factory as ViewFactory;

class ViewSharedVariables implements ServerMiddlewareInterface
{
    /**
     * @var ViewFactory
     */
    private $viewFactory;

    /**
     * @param ViewFactory      $viewFactory
     * @param SessionInterface $session
     */
    public function __construct(ViewFactory $viewFactory)
    {
        $this->viewFactory = $viewFactory;
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
            'session' => $request->getAttribute('_session'),
            'flashes' => $request->getAttribute('_flashes'),
        ]);

        return $delegate->process($request);
    }
}
