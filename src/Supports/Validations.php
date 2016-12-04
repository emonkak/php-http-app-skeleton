<?php

namespace App\Supports;

final class Validations
{
    /**
     * @param array $validations
     * @return callable
     */
    public static function shape(array $validations)
    {
        return function($target) use ($validations) {
            if (!is_array($target)) {
                return false;
            }

            foreach ($validations as $key => $validation) {
                $value = isset($target[$key]) ? $target[$key] : null;

                if (!$validation($value)) {
                    return false;
                }
            }

            return true;
        };
    }

    /**
     * @param callable $validation
     * @return callable
     */
    public static function arrayOf(callable $validation)
    {
        return function($target) use ($validation) {
            if (!is_array($target)) {
                return false;
            }

            foreach ($target as $element) {
                if (!$validation($element)) {
                    return false;
                }
            }

            return true;
        };
    }

    /**
     * @param callable[] $validations
     * @return callable
     */
    public static function oneOf(array $validations)
    {
        return function($target) use ($validations) {
            foreach ($validations as $validation) {
                if ($validation($target)) {
                    return true;
                }
            }

            return false;
        };
    }

    /**
     * @param callable $validation
     * @return callable
     */
    public static function optional(callable $validation)
    {
        return function($target) use ($validation) {
            return $target === null || $validation($target);
        };
    }

    private function __construct()
    {
    }
}
