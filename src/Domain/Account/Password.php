<?php

namespace App\Domain\Account;

class Password
{
    /**
     * @var string
     */
    private $hash;

    public static function generate(string $string): Password
    {
        return new Password(password_hash($string, PASSWORD_BCRYPT));
    }

    public function __construct(string $hash)
    {
        $this->hash = $hash;
    }

    public function verify(string $string): bool
    {
        return password_verify($string, $this->hash);
    }

    public function hash(): string
    {
        return $this->hash;
    }
}
