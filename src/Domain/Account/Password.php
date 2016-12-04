<?php

namespace App\Domain\Account;

class Password
{
    /**
     * @var string
     */
    private $hash;

    /**
     * @param string $string
     * @return Password
     */
    public static function fromString($string)
    {
        return new Password(password_hash($string, PASSWORD_BCRYPT));
    }

    /**
     * @param string $hash
     */
    public function __construct($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @param string $hash
     * @return boolean
     */
    public function verify($string)
    {
        return password_verify($string, $this->hash);
    }

    /**
     * @return string
     */
    public function hash()
    {
        return $this->hash;
    }
}
