<?php

namespace App\Adapters\Http\Handlers;

use App\Supports\Respondable;
use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Xiaoler\Blade\Factory as ViewFactory;
use Zend\Diactoros\Response\HtmlResponse;

class Index implements ServerMiddlewareInterface
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
        $view = $this->viewFactory->make('index');

        return $this->render($view);
    }
}
