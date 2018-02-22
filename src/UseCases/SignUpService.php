<?php

namespace App\UseCases;

use App\Domain\Account\Account;
use App\Domain\Account\AccountRepository;
use App\Domain\Account\EmailAddress;
use App\Domain\Account\Password;
use App\Domain\Account\PasswordPolicy;
use App\Supports\Transactional;
use App\Supports\UnitOfWork;
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
     * @throws DuplicateEmailAddressException
     * @throws InvalidEmailAddressException
     * @throws PassswordPolicyUnsatisfied
     */
    public function signUp(string $emailAddress, string $password): Account
    {
        if (!EmailAddress::isValid($emailAddress)) {
            throw new SignUpException('The email address is invalid.');
        }

        if (!$this->passwordPolicy->isSatisfied($password)) {
            throw new SignUpException('The password does not satisfied the password policy.');
        }

        $unitOfWork = new UnitOfWork([
            Account::class => $this->accountRepository
        ]);

        return $this->transaction(function() use ($emailAddress, $password, $unitOfWork) {
            $existingAccount = $this->accountRepository->lockedAccountOfEmail($emailAddress);
            if ($existingAccount !== null) {
                throw new SignUpException('The email address is already use.');
            }

            $account = Account::create(
                new EmailAddress($emailAddress),
                Password::generate($password)
            );

            $unitOfWork->markedAsNew($account);
            $unitOfWork->flush();

            return $account;
        });
    }
}
