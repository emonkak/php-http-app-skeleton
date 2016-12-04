<?php

namespace App\Adapters\Http\Handlers\Accounts;

use App\Supports\Respondable;
use App\Supports\Validations;
use App\UseCases\SignUpException;
use App\UseCases\SignUpService;
use App\UseCases\AuthenticationService;
use Emonkak\HttpException\BadRequestHttpException;
use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator as V;
use Xiaoler\Blade\Factory as ViewFactory;

class Create implements ServerMiddlewareInterface
{
    use Respondable;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var SignUpService
     */
    private $signUpService;

    /**
     * @var ViewFactory
     */
    private $viewFactory;

    /**
     * @param AuthenticationService $authenticationService
     * @param SignUpService         $signUpService
     * @param ViewFactory           $viewFactory
     */
    public function __construct(
        AuthenticationService $authenticationService,
        SignUpService $signUpService,
        ViewFactory $viewFactory
    ) {
        $this->authenticationService = $authenticationService;
        $this->signUpService = $signUpService;
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
            'password_confirmation' => 'is_string',
        ]);
        if (!$validation($body)) {
            throw new BadRequestHttpException();
        }

        if ($body['password'] !== $body['password_confirmation']) {
            $request->getAttribute('_flashes')
                ->add('danger', 'Password does not match the confirmation password.');
            goto ERROR;
        }

        try {
            $account = $this->signUpService->signUp($body['email_address'], $body['password_confirmation']);
        } catch (SignUpException $e) {
            $request->getAttribute('_flashes')
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
