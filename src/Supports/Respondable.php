<?php

namespace App\Supports;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Xiaoler\Blade\View;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\RedirectResponse;

trait Respondable
{
    /**
     * @param string  $html
     * @param integer $statusCode
     * @param array   $headers
     * @return ResponseInterface
     */
    public function html($html, $statusCode = 200, array $headers = [])
    {
        return new HtmlResponse($html, $statusCode, $headers);
    }

    /**
     * @param View    $view
     * @param integer $statusCode
     * @param array   $headers
     * @return ResponseInterface
     */
    public function render(View $view, $statusCode = 200, array $headers = [])
    {
        return new HtmlResponse($view->render(), $statusCode, $headers);
    }

    /**
     * @param mixed   $data
     * @param integer $statusCode
     * @param array   $headers
     * @return ResponseInterface
     */
    public function json($data, $statusCode = 200, array $headers = [])
    {
        return new JsonResponse($data, $statusCode, $headers);
    }

    /**
     * @param string|UriInterface $uri
     * @param integer             $statusCode
     * @param array               $headers
     * @return ResponseInterface
     */
    public function redirect($uri, $statusCode = 302)
    {
        return new RedirectResponse($uri, $statusCode);
    }
}
