<?php

namespace App\Adapters\Http\Middlewares;

use App\Supports\Respondable;
use Emonkak\HttpException\HttpExceptionInterface;
use Emonkak\HttpMiddleware\ErrorHandlerInterface;
use Emonkak\HttpMiddleware\ErrorMiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Xiaoler\Blade\Factory as ViewFactory;
use Zend\Diactoros\Response\EmptyResponse;

class ErrorPage implements ErrorMiddlewareInterface
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

    public function processError(ServerRequestInterface $request, HttpExceptionInterface $exception, ErrorHandlerInterface $handler): ResponseInterface
    {
        $statusCode = $exception->getStatusCode();
        $statusText = (new EmptyResponse($statusCode))->getReasonPhrase();
        $headers = $exception->getHeaders();

        $view = $this->viewFactory->make('error', [
            'status_code' => $statusCode,
            'status_text' => $statusText,
        ]);

        return $this->render($view, $statusCode, $headers);
    }
}
