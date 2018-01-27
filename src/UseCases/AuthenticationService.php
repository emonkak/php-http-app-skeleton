<?php

namespace App\UseCases;

use App\Domain\Account\Account;
use App\Domain\Account\AccountRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthenticationService
{
    /**
     * @var Accont
     */
    private $account;

    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(
        AccountRepository $accountRepository,
        SessionInterface $session
    ) {
        $this->accountRepository = $accountRepository;
        $this->session = $session;
    }

    public function getAccount(): ?Account
    {
        return $this->account ? $this->account : $this->authenticate();
    }

    public function authenticate(): ?Account
    {
        $accountId = $this->session->get('account_id');

        if ($accountId === null) {
            return null;
        }

        $account = $this->accountRepository->accountOfId($accountId);

        if ($account === null) {
            $this->session->remove('account_id');

            return null;
        }

        return $this->account = $account;
    }

    public function authorize(Account $account): void
    {
        $this->session->set('account_id', $account->getAccountId());
    }

    public function attempt(string $emailAddress, string $password): ?Account
    {
        $account = $this->accountRepository->accountOfEmailAddress($emailAddress);

        if ($account === null) {
            return null;
        }

        if (!$account->getPassword()->verify($password)) {
            return null;
        }

        $this->session->set('account_id', $account->getAccountId());

        return $account;
    }

    public function revoke(): void
    {
        $this->session->remove('account_id');
    }
}
