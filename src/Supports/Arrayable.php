<?php

namespace App\Supports;

trait Arrayable
{
    public function toArray()
    {
        return array_filter(get_object_vars($this), 'is_scalar');
    }
}