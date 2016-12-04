<?php

namespace App\UseCases;

use App\Domain\Account\Account;
use App\Domain\Account\AccountRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthenticationService
{
    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @param AccountRepository $accountRepository
     * @param SessionInterface  $session
     */
    public function __construct(
        AccountRepository $accountRepository,
        SessionInterface $session
    ) {
        $this->accountRepository = $accountRepository;
        $this->session = $session;
    }

    /**
     * @return Account|null
     */
    public function authenticate()
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

        return $account;
    }

    /**
     * @param Account $account
     */
    public function authorize(Account $account)
    {
        $this->session->set('account_id', $account->getAccountId());
    }

    /**
     * @param string $emailAddress
     * @param string $password
     * @return Account|null
     */
    public function attempt($emailAddress, $password)
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

    public function revoke()
    {
        $this->session->remove('account_id');
    }
}
