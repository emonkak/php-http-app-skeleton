<?php

namespace App\Adapter\Http\Handlers\Accounts;

use Emonkak\HttpException\BadRequestHttpException;
use Emonkak\Validation\Types;
use Emonkak\Validation\Validator;
use App\Support\Http\Respondable;
use App\UseCase\AuthenticationService;
use App\UseCase\SignUpException;
use App\UseCase\SignUpService;
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
     * @var SessionInterface
     */
    private $session;

    /**
     * @var SignUpService
     */
    private $signUpService;

    /**
     * @var ViewFactory
     */
    private $viewFactory;

    public function __construct(
        AuthenticationService $authenticationService,
        SessionInterface $session,
        SignUpService $signUpService,
        ViewFactory $viewFactory
    ) {
        $this->authenticationService = $authenticationService;
        $this->session = $session;
        $this->signUpService = $signUpService;
        $this->viewFactory = $viewFactory;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        $errors = (new Validator([
            'email_address' => Types::string(),
            'password' => Types::string(),
            'password_confirmation' => Types::string(),
        ]))->validate($body);
        if (count($errors) > 0) {
            throw new BadRequestHttpException();
        }

        if ($body['password'] !== $body['password_confirmation']) {
            $this->session->getFlashBag()
                ->add('danger', 'Password does not match the confirmation password.');
            goto ERROR;
        }

        try {
            $account = $this->signUpService->signUp($body['email_address'], $body['password_confirmation']);
        } catch (SignUpException $e) {
            $this->session->getFlashBag()
                ->add('danger', $e->getMessage());
            goto ERROR;
        }

        $this->authenticationService->authorize($account);

        return $this->redirect('/');

ERROR:
        $view = $this->viewFactory->make('accounts/sign_up', array_only($body, [
            'email_address',
            'password',
            'password_confirmation',
        ]));

        return $this->render($view, 422);
    }
}
