<?php

namespace App\Support\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Xiaoler\Blade\View;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\RedirectResponse;

trait Respondable
{
    protected function html(string $html, int $statusCode = 200, array $headers = []): ResponseInterface
    {
        return new HtmlResponse($html, $statusCode, $headers);
    }

    protected function render(View $view, int $statusCode = 200, array $headers = []): ResponseInterface
    {
        return new HtmlResponse($view->render(), $statusCode, $headers);
    }

    protected function json($data, int $statusCode = 200, array $headers = []): ResponseInterface
    {
        return new JsonResponse($data, $statusCode, $headers);
    }

    protected function redirect(string $uri, int $statusCode = 302): ResponseInterface
    {
        return new RedirectResponse($uri, $statusCode);
    }
}
