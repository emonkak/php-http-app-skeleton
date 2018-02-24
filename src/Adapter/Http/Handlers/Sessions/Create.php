<?php

namespace App\Adapter\Http\Handlers\Sessions;

use App\Support\Respondable;
use App\Support\Validation\Rules;
use App\Support\Validation\Validator;
use App\Support\Validations;
use App\UseCase\AuthenticationService;
use Emonkak\HttpException\BadRequestHttpException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Xiaoler\Blade\Factory as ViewFactory;

class Create implements RequestHandlerInterface
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

    public function __construct(
        AuthenticationService $authenticationService,
        SessionInterface $session,
        ViewFactory $viewFactory
    ) {
        $this->authenticationService = $authenticationService;
        $this->session = $session;
        $this->viewFactory = $viewFactory;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
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
