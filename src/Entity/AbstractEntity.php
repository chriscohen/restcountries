<?php
declare(strict_types = 1);

namespace ChrisCohen\Entity;

abstract class AbstractEntity
{
    /**
     * @var int
     */
    protected $id;

    /**
     * Whether or not the entity is stored in the database with no changes.
     *
     * @var bool
     */
    protected $inDatabase = false;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function isInDatabase(): bool
    {
        return $this->inDatabase;
    }

    /**
     * @param bool $inDatabase
     */
    public function setInDatabase(bool $inDatabase): void
    {
        $this->inDatabase = $inDatabase;
    }
}
