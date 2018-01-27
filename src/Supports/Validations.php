<?php

namespace App\Supports;

final class Validations
{
    public static function shape(array $validations): callable
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

    public static function arrayOf(callable $validation): callable
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

    public static function oneOf(array $validations): callable
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

    public static function optional(callable $validation): callable
    {
        return function($target) use ($validation) {
            return $target === null || $validation($target);
        };
    }

    private function __construct()
    {
    }
}
