<?php

namespace App\Support\Database;

use Emonkak\Database\PDOInterface;
use Emonkak\Orm\DeleteBuilder;
use Emonkak\Orm\InsertBuilder;
use Emonkak\Orm\UpdateBuilder;
use App\Support\Model\Entity;

trait Persistable
{
    public function persist(Entity $entity): void
    {
        $idAttribute = $entity->getIdAttribute();
        $tableName = $entity->getTableName();
        $data = $entity->serializeArray();

        $pdo = $this->getPdoForEntity($entity);

        (new InsertBuilder())
            ->into($tableName, array_keys($data))
            ->values(array_values($data))
            ->execute($pdo);

        $ref = new \ReflectionObject($entity);

        if ($ref->hasProperty($idAttribute)) {
            $idValue = $pdo->lastInsertId();
            $idProp = $ref->getProperty($idAttribute);
            $idProp->setAccessible(true);
            $idProp->setValue($entity, $idValue);
        }
    }

    public function update(Entity $entity): void
    {
        $entity->touch();

        $idAttribute = $entity->getIdAttribute();
        $tableName = $entity->getTableName();
        $data = $entity->serializeArray();

        $ref = new \ReflectionObject($entity);
        $idProp = $ref->getProperty($idAttribute);
        $idProp->setAccessible(true);
        $idValue = $idProp->getValue($entity);

        $pdo = $this->getPdoForEntity($entity);

        (new UpdateBuilder())
            ->table($tableName)
            ->setAll($data)
            ->where($idAttribute, '=', $idValue)
            ->execute($pdo);
    }

    public function delete(Entity $entity): void
    {
        $idAttribute = $entity->getIdAttribute();
        $tableName = $entity->getTableName();
        $data = $entity->serializeArray();

        $ref = new \ReflectionObject($entity);
        $idProp = $ref->getProperty($idAttribute);
        $idProp->setAccessible(true);
        $idValue = $idProp->getValue($entity);

        $pdo = $this->getPdoForEntity($entity);

        (new DeleteBuilder())
            ->from($tableName)
            ->where($idAttribute, '=', $idValue)
            ->execute($pdo);
    }

    abstract protected function getPdoForEntity(Entity $entity): PDOInterface;
}
