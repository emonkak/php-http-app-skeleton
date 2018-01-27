<?php

namespace App\Adapters\Http\Handlers\Accounts;

use App\Supports\Respondable;
use App\Supports\Validations;
use App\UseCases\AuthenticationService;
use App\UseCases\SignUpException;
use App\UseCases\SignUpService;
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

        $validation = Validations::shape([
            'email_address' => 'is_string',
            'password' => 'is_string',
            'password_confirmation' => 'is_string',
        ]);
        if (!$validation($body)) {
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
