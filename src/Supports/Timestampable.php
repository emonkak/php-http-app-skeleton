<?php

namespace App\Supports;

trait Timestampable
{
    /**
     * @var string
     */
    private $created_at;

    /**
     * @var string
     */
    private $updated_at;

    public function getCreatedAt(): ?\DatetTimeImmutable
    {
        return $this->created_at !== null ? new \DatetTimeImmutable($this->created_at) : null;
    }

    public function getUpdatedAt(): ?\DatetTimeImmutable
    {
        return $this->updated_at !== null ? new \DatetTimeImmutable($this->updated_at) : null;
    }

    private function setCreatedAt(\DateTimeInterface $created_at): void
    {
        $this->created_at = $created_at->format('Y-m-d H:i:s');
    }

    private function setUpdatedAt(\DateTimeInterface $updated_at): void
    {
        $this->updated_at = $updated_at->format('Y-m-d H:i:s');
    }
}
