<?php

namespace App\Supports;

use Emonkak\Orm\DeleteBuilder;
use Emonkak\Orm\InsertBuilder;
use Emonkak\Orm\UpdateBuilder;

class UnitOfWork
{
    /**
     * @var array<string, PersistableInterface>
     */
    private $persistables;

    /**
     * @var SplObjectStorage
     */
    private $objects;

    public function __construct(array $persistables)
    {
        $this->persistables = $persistables;
        $this->objects = new \SplObjectStorage();
    }

    public function getEntityState(Entity $entity): int
    {
        return isset($this->objects[$entity])
            ? $this->objects[$entity]
            : EntityState::DETACHED;
    }

    public function markedAsNew(Entity $entity): void
    {
        $this->ensureManagedEntityClass($entity);

        $this->objects[$entity] = EntityState::NEW;
    }

    public function markedAsChanged(Entity $entity): void
    {
        $this->ensureManagedEntityClass($entity);

        $this->objects[$entity] = EntityState::CHANGED;
    }

    public function markedAsDeleted(Entity $entity): void
    {
        $this->ensureManagedEntityClass($entity);

        $this->objects[$entity] = EntityState::DELETED;
    }

    public function detach(Entity $entity): void
    {
        $this->ensureManagedEntityClass($entity);

        unset($this->objects[$entity]);
    }

    public function clear(): void
    {
        $this->objects = new \SplObjectStorage();
    }

    public function flush(): void
    {
        foreach ($this->objects as $entity) {
            $entityState = $this->objects[$entity];
            $entityClass = get_class($entity);
            $persistable = $this->persistables[$entityClass];

            switch ($entityState) {
                case EntityState::NEW:
                    $persistable->persist($entity);
                    break;
                case EntityState::CHANGED:
                    $persistable->update($entity);
                    break;
                case EntityState::DELETED:
                    $persistable->delete($entity);
                    break;
            }
        }

        $this->clear();
    }

    private function ensureManagedEntityClass(Entity $entity): void
    {
        $entityClass = get_class($entity);

        if (!isset($this->persistables[$entityClass])) {
            throw new \InvalidArgumentException("'$entityClass' is not managed entity class in this unit of work.");
        }
    }
}
