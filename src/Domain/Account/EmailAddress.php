<?php

namespace App\Domain\Account;

class EmailAddress
{
    /**
     * @var string
     */
    private $address;

    /**
     * @param string $address
     * @return boolean
     */
    public static function isValid($address)
    {
        return filter_var($address, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @param string $address
     */
    public function __construct($address)
    {
        if (!self::isValid($address)) {
            throw new \InvalidArgumentException('Invalid email address');
        }

        $this->address = $address;
    }

    /**
     * @return string
     */
    public function address()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->address;
    }
}
