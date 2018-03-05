<?php

namespace App\Adapter\Http\Handlers\Accounts;

use App\Support\Http\Respondable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Xiaoler\Blade\Factory as ViewFactory;

class SignUp implements RequestHandlerInterface
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
        $view = $this->viewFactory->make('accounts/sign_up');

        return $this->render($view);
    }
}
