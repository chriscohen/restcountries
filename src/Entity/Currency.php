<?php
declare(strict_types = 1);

namespace ChrisCohen\Entity;

use stdClass;

class Currency extends AbstractEntity
{
    /**
     * @var string
     */
    protected $symbol;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $code;

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     */
    public function setSymbol(string $symbol): void
    {
        $this->symbol = $symbol;
    }

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
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * Format this entity as a class that can be converted to JSON.
     *
     * @return stdClass
     */
    public function toJson() : stdClass
    {
        $output = new stdClass();

        $output->id = $this->getId();
        $output->name = $this->getName();
        $output->code = $this->getCode();
        $output->symbol = $this->getSymbol();

        return $output;
    }
}
