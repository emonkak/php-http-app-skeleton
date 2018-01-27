<?php

namespace App\Domain\Account;

use App\Supports\Arrayable;
use App\Supports\Timestampable;

class Account
{
    use Arrayable;
    use Timestampable;

    /**
     * @var integer
     */
    private $account_id;

    /**
     * @var string
     */
    private $email_address;

    /**
     * @var string
     */
    private $password;

    public static function create(EmailAddress $emailAddress, Password $password)
    {
        $account = new Account();
        $account->setEmailAddress($emailAddress);
        $account->setPassword($password);
        return $account;
    }

    public function getAccountId(): int
    {
        return $this->account_id;
    }

    public function getEmailAddress(): EmailAddress
    {
        return new EmailAddress($this->email_address);
    }

    public function getPassword(): Password
    {
        return new Password($this->password);
    }

    private function setAccountId(int $account_id): void
    {
        $this->account_id = $account_id;
    }

    private function setEmailAddress(EmailAddress $email_address): void
    {
        $this->email_address = $email_address->address();
    }

    private function setPassword(Password $password): void
    {
        $this->password = $password->hash();
    }
}
