<?php
declare(strict_types = 1);

namespace ChrisCohen\Entity;

use stdClass;

class TimeZone extends AbstractEntity
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Format this entity as an object that can be converted to JSON.
     *
     * @return stdClass
     */
    public function toJson() : stdClass
    {
        $output = new stdClass();

        $output->id = $this->getId();
        $output->name = $this->getName();

        return $output;
    }
}
