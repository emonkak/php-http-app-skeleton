<?php

namespace App\Adapter\Http\Handlers;

use App\Support\Respondable;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Xiaoler\Blade\Factory as ViewFactory;
use Zend\Diactoros\Response\HtmlResponse;

class Index implements RequestHandlerInterface
{
    use Respondable;

    /**
     * @var ViewFactory
     */
    private $viewFactory;

    public function __construct(ViewFactory $viewFactory)
    {
        $this->viewFactory = $viewFactory;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $view = $this->viewFactory->make('index');

        return $this->render($view);
    }
}
