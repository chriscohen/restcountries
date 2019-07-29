<?php
declare(strict_types = 1);

namespace ChrisCohen\Entity;

use stdClass;

class Country extends AbstractEntity
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $capital;

    /**
     * @var string
     */
    protected $alpha2;

    /**
     * @var string
     */
    protected $alpha3;

    /**
     * @var string
     */
    protected $numericCode;

    /**
     * @var string
     */
    protected $callingCode;

    /**
     * @var string
     */
    protected $flagUrl;

    /**
     * @var string
     */
    protected $region;

    /**
     * @var Currency[]
     */
    protected $currencies = [];

    /**
     * @var TimeZone[]
     */
    protected $timezones = [];

    /**
     * @var Language[]
     */
    protected $languages = [];

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
    public function getCapital(): string
    {
        return $this->capital;
    }

    /**
     * @param string $capital
     */
    public function setCapital(string $capital): void
    {
        $this->capital = $capital;
    }

    /**
     * @return string
     */
    public function getAlpha2(): string
    {
        return $this->alpha2;
    }

    /**
     * @param string $alpha2
     */
    public function setAlpha2(string $alpha2): void
    {
        $this->alpha2 = $alpha2;
    }

    /**
     * @return string
     */
    public function getAlpha3(): string
    {
        return $this->alpha3;
    }

    /**
     * @param string $alpha3
     */
    public function setAlpha3(string $alpha3): void
    {
        $this->alpha3 = $alpha3;
    }

    /**
     * @return string
     */
    public function getNumericCode(): string
    {
        return $this->numericCode;
    }

    /**
     * @param string $numericCode
     */
    public function setNumericCode(string $numericCode): void
    {
        $this->numericCode = $numericCode;
    }

    /**
     * @return string
     */
    public function getCallingCode(): string
    {
        return $this->callingCode;
    }

    /**
     * @param string $callingCode
     */
    public function setCallingCode(string $callingCode): void
    {
        $this->callingCode = $callingCode;
    }

    /**
     * @return string
     */
    public function getFlagUrl(): string
    {
        return $this->flagUrl;
    }

    /**
     * @param string $flagUrl
     */
    public function setFlagUrl(string $flagUrl): void
    {
        $this->flagUrl = $flagUrl;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @param string $region
     */
    public function setRegion(string $region): void
    {
        $this->region = $region;
    }

    /**
     * @return Currency[]
     */
    public function getCurrencies(): array
    {
        return $this->currencies;
    }

    /**
     * @param Currency[] $currencies
     */
    public function setCurrencies(array $currencies): void
    {
        $this->currencies = $currencies;
    }

    /**
     * @param Currency $currency
     */
    public function addCurrency(Currency $currency)
    {
        $this->currencies[$currency->getId()] = $currency;
    }

    /**
     * @return TimeZone[]
     */
    public function getTimezones(): array
    {
        return $this->timezones;
    }

    /**
     * @param TimeZone[] $timezones
     */
    public function setTimezones(array $timezones): void
    {
        $this->timezones = $timezones;
    }

    /**
     * @param TimeZone $timezone
     */
    public function addTimezone(TimeZone $timezone)
    {
        $this->timezones[$timezone->getId()] = $timezone;
    }

    /**
     * @return Language[]
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    /**
     * @param Language[] $languages
     */
    public function setLanguages(array $languages): void
    {
        $this->languages = $languages;
    }

    /**
     * @param Language $language
     */
    public function addLanguage(Language $language)
    {
        $this->languages[$language->getId()] = $language;
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
        $output->capital = $this->getCapital();
        $output->alpha2 = $this->getAlpha2();
        $output->alpha3 = $this->getAlpha3();
        $output->numericCode = $this->getNumericCode();
        $output->callingCode = $this->getCallingCode();
        $output->flagUrl = $this->getFlagUrl();
        $output->region = $this->getRegion();

        $output->currencies = [];

        foreach ($this->getCurrencies() as $currency) {
            $output->currencies[] = $currency->toJson();
        }

        $output->languages = [];

        foreach ($this->getLanguages() as $language) {
            $output->languages[] = $language->toJson();
        }

        $output->timezones = [];

        foreach ($this->getTimezones() as $timezone) {
            $output->timezones[] = $timezone->toJson();
        }

        return $output;
    }
}
