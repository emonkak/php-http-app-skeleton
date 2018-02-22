<?php

namespace App\Supports;

final class EntityState
{
    const DETACHED = 0;
    const NEW = 1;
    const CHANGED = 2;
    const DELETED = 3;

    private function __construct()
    {
    }
}
