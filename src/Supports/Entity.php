<?php

namespace App\Supports;

abstract class Entity
{
    /**
     * @var string
     */
    protected $created_at;

    /**
     * @var string
     */
    protected $updated_at;

    public function __construct()
    {
        if ($this->created_at === null) {
            $this->created_at = $this->updated_at = date('Y-m-d H:i:s');
        }
    }

    public function getTableName(): string
    {
        return snake_case(class_basename(static::class)) . 's';
    }

    public function getIdAttribute(): string
    {
        return snake_case(class_basename(static::class)) . '_id';
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->created_at);
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return new \DateTimeImmutable($this->updated_at);
    }

    public function touch(): void
    {
        $this->updated_at = date('Y-m-d H:i:s');
    }

    public function serializeArray(): array
    {
        $data = [];
        foreach ($this as $attribute => $value) {
            if ($value === null || is_scalar($value)) {
                $data[$attribute] = $value;
            }
        }
        return $data;
    }

    protected function setCreatedAt(\DateTimeInterface $created_at): void
    {
        $this->created_at = $created_at->format('Y-m-d H:i:s');
    }

    protected function setUpdatedAt(\DateTimeInterface $updated_at): void
    {
        $this->updated_at = $updated_at->format('Y-m-d H:i:s');
    }
}
