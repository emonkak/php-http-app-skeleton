<?php

namespace App\Support;

interface PersistableInterface
{
    public function persist(Entity $entity): void;

    public function update(Entity $entity): void;

    public function delete(Entity $entity): void;
}
