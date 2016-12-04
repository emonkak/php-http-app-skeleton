<?php

namespace App\Adapters\Http\Handlers\Sessions;

use App\Supports\Respondable;
use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Xiaoler\Blade\Factory as ViewFactory;

class Login implements ServerMiddlewareInterface
{
    use Respondable;

    /**
     * @var ViewFactory
     */
    private $viewFactory;

    /**
     * @param ViewFactory $viewFactory
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
        $view = $this->viewFactory->make('sessions/login');

        return $this->render($view);
    }
}
