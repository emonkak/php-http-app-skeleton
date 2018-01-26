<?php

namespace App\Adapters\Http\Middlewares;

use App\Domain\Account\Account;
use App\UseCases\AuthenticationService;
use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;

class Authenticator implements ServerMiddlewareInterface
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

    /**
     * @param AuthenticationService $authenticationService
     */
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
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
        return $delegate->process($request);
    }
}
