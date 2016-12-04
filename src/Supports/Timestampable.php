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

    /**
     * @return \DatetTimeImmutable|null
     */
    public function getCreatedAt()
    {
        return $this->created_at !== null ? new \DatetTimeImmutable($this->created_at) : null;
    }

    /**
     * @return \DatetTimeImmutable|null
     */
    public function getUpdatedAt()
    {
        return $this->updated_at !== null ? new \DatetTimeImmutable($this->updated_at) : null;
    }

    /**
     * @param \DateTimeInterface $created_at
     */
    public function setCreatedAt(\DateTimeInterface $created_at)
    {
        $this->created_at = $created_at->format('Y-m-d H:i:s');
    }

    /**
     * @param \DateTimeInterface $created_at
     */
    public function setUpdatedAt(\DateTimeInterface $updated_at)
    {
        $this->updated_at = $updated_at->format('Y-m-d H:i:s');
    }
}
