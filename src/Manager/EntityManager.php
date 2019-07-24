<?php
declare(strict_types = 1);

namespace ChrisCohen\Manager;

use ChrisCohen\Connection\DatabaseConnection;
use ChrisCohen\Entity\Country;
use ChrisCohen\Entity\Currency;
use ChrisCohen\Entity\Language;
use ChrisCohen\Entity\TimeZone;
use ChrisCohen\Exception\ChrisCohenException;
use stdClass;

/**
 * Loads and saves entities to and from the database.
 *
 * In a more complete solution, we would implement a manager or repository class for each type of entity. Here, because
 * this is a limited example, and we know there are only four types of entity, we will do them all in one class.
 *
 * @package ChrisCohen\Manager
 */
class EntityManager
{
    /**
     * @var DatabaseConnection
     */
    protected $db;

    /**
     * @var Country[]
     */
    protected $countries;

    /**
     * @var Currency[]
     */
    protected $currencies;

    /**
     * @var Language[]
     */
    protected $languages;

    /**
     * @var TimeZone[]
     */
    protected $timezones;

    public function __construct()
    {
        // In a more complete solution, this would be dependency-injected into the constructor, but since we are not
        // using a services / DI framework or model here, we will simply create a connection in the constructor.
        $this->db = new DatabaseConnection();
        $this->initialise();
    }

    /**
     * Load all data from the database and map accordingly.
     */
    public function initialise()
    {
        // First, load everything with no mapping.
        $this->loadCurrencies();
        $this->loadLanguages();
        $this->loadTimezones();
        $this->loadCountries();

        // Map everything to countries.
        try {
            $this->mapCurrencies();
            $this->mapLanguages();
            $this->mapTimezones();
        } catch (ChrisCohenException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Load all countries from the database.
     */
    public function loadCountries()
    {
        $this->countries = [];

        $result = $this->db->query("SELECT * FROM countries");

        foreach ($result as $row) {
            $country = new Country();
            $country->setId($row['id']);
            $country->setName($row['name']);
            $country->setCapital($row['capital']);
            $country->setAlpha2($row['alpha2']);
            $country->setAlpha3($row['alpha3']);
            $country->setNumericCode($row['numeric_code']);
            $country->setCallingCode($row['calling_code']);
            $country->setRegion($row['region']);
            $country->setFlagUrl($row['flag_url']);

            $this->countries[$country->getId()] = $country;
        }
    }

    /**
     * Load all currencies from the database.
     */
    public function loadCurrencies()
    {
        $this->currencies = [];

        $result = $this->db->query("SELECT * FROM currencies");

        foreach ($result as $row) {
            $currency = new Currency();
            $currency->setId($row['id']);
            $currency->setName($row['name']);
            $currency->setCode($row['code']);
            $currency->setSymbol($row['symbol']);

            $this->currencies[$currency->getId()] = $currency;
        }
    }

    /**
     * Load all timezones from the database.
     */
    public function loadTimezones()
    {
        $this->timezones = [];

        $result = $this->db->query("SELECT * FROM timezones");

        foreach ($result as $row) {
            $timezone = new TimeZone();
            $timezone->setId($row['id']);
            $timezone->setName($row['name']);

            $this->timezones[$timezone->getId()] = $timezone;
        }
    }

    /**
     * Load all languages from the database.
     *
     */
    public function loadLanguages()
    {
        $this->languages = [];

        $result = $this->db->query("SELECT * FROM langs");

        foreach ($result as $row) {
            $language = new Language();
            $language->setId($row['id']);
            $language->setName($row['name']);
            $language->setIso6391($row['iso639_1']);
            $language->setIso6392($row['iso639_2']);
            $language->setNativeName($row['native_name']);

            $this->languages[$language->getId()] = $language;
        }
    }

    /**
     * @throws ChrisCohenException
     */
    public function mapCurrencies()
    {
        $result = $this->db->query("SELECT * FROM countries_currencies");

        foreach ($result as $row) {
            $countryId = $row['country'];
            $currencyId = $row['currency'];

            // Make sure both entities exist.
            if (($country = $this->findCountry($countryId)) === null) {
                throw new ChrisCohenException(sprintf('Could not find country with ID %d', $countryId));
            }
            if (($currency = $this->findCurrency($currencyId)) === null) {
                throw new ChrisCohenException(sprintf('Could not find currency with ID %d', $currencyId));
            }

            $country->addCurrency($currency);
        }
    }

    /**
     * @throws ChrisCohenException
     */
    public function mapTimezones()
    {
        $result = $this->db->query("SELECT * FROM countries_timezones");

        foreach ($result as $row) {
            $countryId = $row['country'];
            $timezoneId = $row['timezone'];

            // Make sure both entities exist.
            if (($country = $this->findCountry($countryId)) === null) {
                throw new ChrisCohenException('Could not find country with ID %d', $countryId);
            }
            if (($timezone = $this->findTimezone($timezoneId)) === null) {
                throw new ChrisCohenException('Could not find timezone with ID %d', $timezoneId);
            }

            $country->addTimezone($timezone);
        }
    }

    /**
     * @throws ChrisCohenException
     */
    public function mapLanguages()
    {
        $result = $this->db->query("SELECT * FROM countries_langs");

        foreach ($result as $row) {
            $countryId = $row['country'];
            $languageId = $row['lang'];

            // Make sure both entities exist.
            if (($country = $this->findCountry($countryId)) === null) {
                throw new ChrisCohenException('Could not find country with ID %d', $countryId);
            }
            if (($language = $this->findLanguage($languageId)) === null) {
                throw new ChrisCohenException('Could not find language with ID %d', $languageId);
            }

            $country->addLanguage($language);
        }
    }

    /**
     * @return Country[]
     */
    public function getCountries(): array
    {
        return $this->countries;
    }

    /**
     * @param Country[] $countries
     */
    public function setCountries(array $countries): void
    {
        $this->countries = $countries;
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
     * @param int $id
     *
     * @return Language|null
     */
    public function findLanguage(int $id) : ?Language
    {
        return $this->languages[$id] ?? null;
    }

    /**
     * @param int $id
     *
     * @return Currency|null
     */
    public function findCurrency(int $id) : ?Currency
    {
        return $this->currencies[$id] ?? null;
    }

    /**
     * @param int $id
     *
     * @return Country|null
     */
    public function findCountry(int $id) : ?Country
    {
        return $this->countries[$id] ?? null;
    }

    /**
     * @param int $id
     *
     * @return TimeZone|null
     */
    public function findTimezone(int $id) : ?TimeZone
    {
        return $this->timezones[$id] ?? null;
    }

    /**
     * Format all country entities as an array that can be converted to JSON.
     *
     * @return array
     */
    public function toJson() : array
    {
        $output = [];

        foreach ($this->getCountries() as $country) {
            $output[] = $country->toJson();
        }

        return $output;
    }
}
