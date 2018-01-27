<?php

namespace App\Adapters\Http\Handlers\Sessions;

use App\Supports\Respondable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Xiaoler\Blade\Factory as ViewFactory;

class Login implements RequestHandlerInterface
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
        $view = $this->viewFactory->make('sessions/login');

        return $this->render($view);
    }
}
