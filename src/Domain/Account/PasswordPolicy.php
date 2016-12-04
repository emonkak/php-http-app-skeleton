<?php

namespace App\Domain\Account;

class PasswordPolicy
{
    /**
     * @param string $password
     * @return boolean
     */
    public function isSatisfied($password)
    {
        return strlen($password) >= 6;
    }
}
