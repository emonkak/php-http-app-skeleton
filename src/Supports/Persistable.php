<?php

namespace App\Supports;

use Emonkak\Database\PDOInterface;
use Emonkak\Orm\InsertBuilder;

trait Persistable
{
    /**
     * @param string       $table
     * @param object       $entity
     * @param PDOInterface $pdo
     */
    public function persist($table, $entity, PDOInterface $pdo)
    {
        $array = $entity->toArray();

        (new InsertBuilder())
            ->into($table, array_keys($array))
            ->values(array_values($array))
            ->execute($pdo);

        $class = get_class($entity);
        $idAttribute = snake_case(class_basename($class)) . '_id';

        $setter = \Closure::bind(static function($entity, $key, $value) {
            $entity->$key = $value;
        }, null, $class);

        $setter($entity, $idAttribute, $pdo->lastInsertId());

        if (method_exists($entity, 'setCreatedAt')) {
            $entity->setCreatedAt(new \DateTime());
        }
    }
}
