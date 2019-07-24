<?php
declare(strict_types = 1);

namespace ChrisCohen\Entity;

use stdClass;

class Language extends AbstractEntity
{
    /**
     * @var string
     */
    protected $iso6391;

    /**
     * @var string
     */
    protected $iso6392;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $nativeName;

    /**
     * @return string
     */
    public function getIso6391(): string
    {
        return $this->iso6391;
    }

    /**
     * @param string $iso6391
     */
    public function setIso6391(string $iso6391): void
    {
        $this->iso6391 = $iso6391;
    }

    /**
     * @return string
     */
    public function getIso6392(): string
    {
        return $this->iso6392;
    }

    /**
     * @param string $iso6392
     */
    public function setIso6392(string $iso6392): void
    {
        $this->iso6392 = $iso6392;
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
    public function getNativeName(): string
    {
        return $this->nativeName;
    }

    /**
     * @param string $nativeName
     */
    public function setNativeName(string $nativeName): void
    {
        $this->nativeName = $nativeName;
    }

    public function toJson() : stdClass
    {
        $output = new stdClass();

        $output->id = $this->getId();
        $output->iso639_1 = $this->getIso6391();
        $output->iso639_2 = $this->getIso6392();
        $output->name = $this->getName();
        $output->nativeName = $this->getNativeName();

        return $output;
    }
}
