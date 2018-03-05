<?php

namespace App\Domain\Account;

use App\Support\Model\Entity;

class Account extends Entity
{
    /**
     * @var string
     */
    protected $account_id;

    /**
     * @var string
     */
    protected $email_address;

    /**
     * @var string
     */
    protected $password;

    public static function create(EmailAddress $email_address, Password $password): self
    {
        $account = new Account();
        $account->setEmailAddress($email_address);
        $account->setPassword($password);
        return $account;
    }

    public function getAccountId(): int
    {
        return (int) $this->account_id;
    }

    public function getEmailAddress(): EmailAddress
    {
        return new EmailAddress($this->email_address);
    }

    public function getPassword(): Password
    {
        return new Password($this->password);
    }

    protected function setAccountId(int $account_id): void
    {
        $this->account_id = (string) $account_id;
    }

    protected function setEmailAddress(EmailAddress $email_address): void
    {
        $this->email_address = $email_address->address();
    }

    protected function setPassword(Password $password): void
    {
        $this->password = $password->hash();
    }
}
