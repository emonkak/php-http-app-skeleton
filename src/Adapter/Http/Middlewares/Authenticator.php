<?php

namespace App\Adapter\Http\Middlewares;

use App\Domain\Account\Account;
use App\UseCase\AuthenticationService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;

class Authenticator implements MiddlewareInterface
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var string[]
     */
    private $excludePaths = [
        '/accounts',
        '/sessions',
    ];

    /**
     * @var string
     */
    private $redirectTo = '/sessions/login';

    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $account = $this->authenticationService->authenticate();
        if ($account === null) {
            $path = $request->getUri()->getPath();
            $excluded = false;

            foreach ($this->excludePaths as $excludePath) {
                if (strpos($path, $excludePath) === 0) {
                    $excluded = true;
                    break;
                }
            }

            if (!$excluded) {
                return new RedirectResponse($this->redirectTo);
            }
        }
        return $handler->handle($request);
    }
}
