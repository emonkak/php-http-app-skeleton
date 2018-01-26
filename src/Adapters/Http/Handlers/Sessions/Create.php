<?php

namespace App\Adapters\Http\Handlers\Sessions;

use App\Supports\Respondable;
use App\Supports\Validation\Rules;
use App\Supports\Validation\Validator;
use App\Supports\Validations;
use App\UseCases\AuthenticationService;
use Emonkak\HttpException\BadRequestHttpException;
use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Xiaoler\Blade\Factory as ViewFactory;

class Create implements ServerMiddlewareInterface
{
    use Respondable;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var ViewFactory
     */
    private $viewFactory;

    /**
     * @param AuthenticationService $authenticationService
     * @param SessionInterface      $session
     * @param ViewFactory           $viewFactory
     */
    public function __construct(
        AuthenticationService $authenticationService,
        SessionInterface $session,
        ViewFactory $viewFactory
    ) {
        $this->authenticationService = $authenticationService;
        $this->session = $session;
        $this->viewFactory = $viewFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $body = $request->getParsedBody();

        $validation = Validations::shape([
            'email_address' => 'is_string',
            'password' => 'is_string',
        ]);
        if (!$validation($body)) {
            throw new BadRequestHttpException();
        }

        $account = $this->authenticationService->attempt(
            $body['email_address'],
            $body['password']
        );
        if ($account === null) {
            $this->session->getFlashBag()
                ->add('danger', 'You entered the wrong email address or password. Please re-enter your email address and password.');
            goto ERROR;
        }

        return $this->redirect('/');

ERROR:
        $variables = array_only($body, ['email_address', 'password']);
        $view = $this->viewFactory->make('sessions/login', $variables);

        return $this->render($view, 422);
    }
}
