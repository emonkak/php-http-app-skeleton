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

    /**
     * @param EmailAddress $emailAddress
     * @param Password     $password
     */
    public static function create(EmailAddress $emailAddress, Password $password)
    {
        $account = new Account();
        $account->setEmailAddress($emailAddress);
        $account->setPassword($password);
        return $account;
    }

    /**
     * @return integer
     */
    public function getAccountId()
    {
        return $this->account_id;
    }

    /**
     * @return EmailAddress
     */
    public function getEmailAddress()
    {
        return new EmailAddress($this->email_address);
    }

    /**
     * @return Password
     */
    public function getPassword()
    {
        return new Password($this->password);
    }

    /**
     * @param integer $account_id
     */
    public function setAccountId($account_id)
    {
        $this->account_id = $account_id;
    }

    /**
     * @param EmailAddress $email_address
     */
    public function setEmailAddress(EmailAddress $email_address)
    {
        $this->email_address = $email_address->address();
    }

    /**
     * @param Password $password
     */
    public function setPassword(Password $password)
    {
        $this->password = $password->hash();
    }
}
