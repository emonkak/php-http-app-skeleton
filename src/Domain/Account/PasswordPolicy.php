<?php

namespace App\Domain\Account;

class PasswordPolicy
{
    public function isSatisfied(string $password): bool
    {
        return strlen($password) >= 6;
    }
}
