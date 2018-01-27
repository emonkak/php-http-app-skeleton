<?php

namespace App\Supports;

use Emonkak\Database\PDOInterface;
use Emonkak\Orm\InsertBuilder;

trait Persistable
{
    public function persist(string $table, $entity, PDOInterface $pdo): void
    {
        $array = $entity->toArray();

        (new InsertBuilder())
            ->into($table, array_keys($array))
            ->values(array_values($array))
            ->execute($pdo);

        $ref = new \ReflectionObject($entity);

        $idValue = $pdo->lastInsertId();
        $idAttribute = snake_case($ref->getShortName()) . '_id';

        if ($ref->hasProperty($idAttribute)) {
            $prop = $ref->getProperty($idAttribute);
            $prop->setAccessible(true);
            $prop->setValue($entity, $idValue);
        }

        $persistedAt = date('Y-m-d H:i:s');

        foreach (['created_at', 'updated_at'] as $attribute) {
            if ($ref->hasProperty($attribute)) {
                $prop = $ref->getProperty($attribute);
                $prop->setAccessible(true);
                $prop->setValue($entity, $persistedAt);
            }
        }
    }
}
