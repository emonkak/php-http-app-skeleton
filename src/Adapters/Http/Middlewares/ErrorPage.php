<?php

namespace App\Adapters\Http\Middlewares;

use App\Supports\Respondable;
use Emonkak\HttpException\HttpExceptionInterface;
use Emonkak\HttpMiddleware\ErrorDelegateInterface;
use Emonkak\HttpMiddleware\ErrorMiddlewareInterface;
use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Xiaoler\Blade\Factory as ViewFactory;
use Zend\Diactoros\Response\EmptyResponse;

class ErrorPage implements ErrorMiddlewareInterface, ServerMiddlewareInterface
{
    use Respondable;

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
        $statusCode = 404;
        $statusText = (new EmptyResponse($statusCode))->getReasonPhrase();

        $view = $this->viewFactory->make('error', [
            'status_code' => $statusCode,
            'status_text' => $statusText,
        ]);

        return $this->render($view);
    }

    /**
     * {@inheritDoc}
     */
    public function processError(ServerRequestInterface $request, HttpExceptionInterface $exception, ErrorDelegateInterface $delegate)
    {
        $statusCode = $exception->getStatusCode();
        $statusText = (new EmptyResponse($statusCode))->getReasonPhrase();

        $view = $this->viewFactory->make('error', [
            'status_code' => $statusCode,
            'status_text' => $statusText,
        ]);

        return $this->render($view, $statusCode, $exception->getHeaders());
    }
}
