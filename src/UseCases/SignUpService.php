<?php

namespace App\UseCases;

use App\Domain\Account\EmailAddress;
use App\Domain\Account\Password;
use App\Domain\Account\PasswordPolicy;
use App\Domain\Account\Account;
use App\Domain\Account\AccountRepository;
use App\Supports\Transactional;
use Emonkak\Database\PDOTransactionInterface;

class SignUpService
{
    use Transactional;

    /**
     * @var PasswordPolicy
     */
    private $passwordPolicy;

    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * @param PDOTransactionInterface $transaction
     * @param PasswordPolicy          $passwordPolicy
     * @param AccountRepository       $accountRepository
     */
    public function __construct(
        PDOTransactionInterface $transaction,
        PasswordPolicy $passwordPolicy,
        AccountRepository $accountRepository
    ) {
        $this->transaction = $transaction;
        $this->passwordPolicy = $passwordPolicy;
        $this->accountRepository = $accountRepository;
    }

    /**
     * @param string $emailAddress
     * @param string $password
     * @return Account
     * @throws DuplicateEmailAddressException
     * @throws InvalidEmailAddressException
     * @throws PassswordPolicyUnsatisfied
     */
    public function signUp($emailAddress, $password)
    {
        if (!EmailAddress::isValid($emailAddress)) {
            throw new SignUpException('The email address is invalid.');
        }

        if (!$this->passwordPolicy->isSatisfied($password)) {
            throw new SignUpException('The password does not satisfied the password policy.');
        }

        return $this->transaction(function() use ($emailAddress, $password) {
            $existingAccount = $this->accountRepository->lockedAccountOfEmail($emailAddress);
            if ($existingAccount !== null) {
                throw new SignUpException('The email address is already use.');
            }

            $account = Account::create(
                new EmailAddress($emailAddress),
                Password::fromString($password)
            );

            $this->accountRepository->store($account);

            return $account;
        });
    }
}
