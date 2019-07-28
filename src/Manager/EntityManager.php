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
            $country->setId((int) $row['id']);
            $country->setName($row['name']);
            $country->setCapital($row['capital']);
            $country->setAlpha2($row['alpha2']);
            $country->setAlpha3($row['alpha3']);
            $country->setNumericCode($row['numeric_code']);
            $country->setCallingCode($row['calling_code']);
            $country->setRegion($row['region']);
            $country->setFlagUrl($row['flag_url']);
            $country->setInDatabase(true);

            $this->addCountry($country);
        }
    }

    /**
     * Add a country to the internal collection.
     *
     * @param Country $country
     */
    public function addCountry(Country $country)
    {
        $this->countries[$country->getId()] = $country;
    }

    /**
     * Add a currency to the internal collection.
     *
     * @param Currency $currency
     */
    public function addCurrency(Currency $currency)
    {
        $this->currencies[$currency->getId()] = $currency;
    }

    /**
     * Add a language to the internal collection.
     *
     * @param Language $language
     */
    public function addLanguage(Language $language)
    {
        $this->languages[$language->getId()] = $language;
    }

    /**
     * Add a timezone to the internal collection.
     *
     * @param TimeZone $timezone
     */
    public function addTimeZone(TimeZone $timezone)
    {
        $this->timezones[$timezone->getId()] = $timezone;
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
            $currency->setId((int) $row['id']);
            $currency->setName($row['name']);
            $currency->setCode($row['code']);
            $currency->setSymbol($row['symbol']);
            $currency->setInDatabase(true);

            $this->addCurrency($currency);
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
            $timezone->setId((int) $row['id']);
            $timezone->setName($row['name']);
            $timezone->setInDatabase(true);

            $this->addTimeZone($timezone);
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
            $language->setId((int) $row['id']);
            $language->setName($row['name']);
            $language->setIso6391($row['iso639_1']);
            $language->setIso6392($row['iso639_2']);
            $language->setNativeName($row['native_name']);
            $language->setInDatabase(true);

            $this->addLanguage($language);
        }
    }

    /**
     * @throws ChrisCohenException
     */
    public function mapCurrencies()
    {
        $result = $this->db->query("SELECT * FROM countries_currencies");

        foreach ($result as $row) {
            $countryId = (int) $row['country'];
            $currencyId = (int) $row['currency'];

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
            $countryId = (int) $row['country'];
            $timezoneId = (int) $row['timezone'];

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
            $countryId = (int) $row['country'];
            $languageId = (int) $row['lang'];

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
     * Find a country by name in the existing data.
     *
     * @param string $name
     *
     * @return Country|null
     */
    public function findCountryByName(string $name) : ?Country
    {
        foreach ($this->getCountries() as $country) {
            if ($country->getName() === $name) {
                return $country;
            }
        }

        return null;
    }

    /**
     * Find a currency by code in the existing data.
     *
     * @param string $code
     *
     * @return Currency|null
     */
    public function findCurrencyByCode(string $code) : ?Currency
    {
        foreach ($this->getCurrencies() as $currency) {
            if ($currency->getCode() === $code) {
                return $currency;
            }
        }

        return null;
    }

    /**
     * Find a language by ISO 639-1 code in the existing data.
     *
     * @param string $iso639_1
     *
     * @return Language|null
     */
    public function findLanguageByIso6391(string $iso639_1) : ?Language
    {
        foreach ($this->getLanguages() as $language) {
            if ($language->getIso6391() === $iso639_1) {
                return $language;
            }
        }

        return null;
    }

    /**
     * Find a timezone by name in the existing data.
     *
     * @param string $name
     *
     * @return TimeZone|null
     */
    public function findTimezoneByName(string $name) : ?TimeZone
    {
        foreach ($this->getTimezones() as $timezone) {
            if ($timezone->getName() === $name) {
                return $timezone;
            }
        }

        return null;
    }

    /**
     * Parse all countries from a JSON array.
     *
     * @param array $json
     */
    public function parseCountries(array $json)
    {
        foreach ($json as $item) {
            // Attempt to find the country in our existing data.
            $countryName = $item->name;
            $existing = $this->findCountryByName($countryName);

            if (!empty($existing)) {
                // We already know about this country so we don't need to process it.
                continue;
            }

            // Create new country and set up basic fields.
            $country = new Country();
            $country->setName($countryName);
            $country->setAlpha2($item->alpha2Code);
            $country->setAlpha3($item->alpha3Code);
            // Some countries don't have numeric codes in the API.
            $country->setNumericCode($item->numericCode ?? '');
            $country->setCapital($item->capital);

            // There can be multiple calling codes per country. Our instructions call for only one calling code, so we
            // are assuming we only need to store one. Note also that although we could use array_shift() here, this
            // will cause the original array to be manipulated, which is less efficient than using reset().
            $country->setCallingCode(reset($item->callingCodes));

            $country->setFlagUrl($item->flag);
            $country->setRegion($item->region);

            // We need to save the country to the database so we can add its ID, before handling the other mapped
            // fields.
            $this->insertCountry($country);

            // Parse currencies, languages, timezones for this country.
            $this->parseCurrencies($item->currencies, $country);
            $this->parseLanguages($item->languages, $country);
            $this->parseTimeZones($item->timezones, $country);

            // Add country to internal collection.
            $this->addCountry($country);
        }
    }

    /**
     * Parse currencies from inside a country in a JSON array.
     *
     * @param array $json
     * @param Country $country
     */
    public function parseCurrencies(array $json, Country $country)
    {
        foreach ($json as $item) {
            // There is bad data in the API where some currencies don't have codes. If we encounter one, ignore that
            // currency.
            if (empty($item->code) || $item->code === '(none)') {
                continue;
            }

            $currency = $this->findCurrencyByCode($item->code);

            // If the currency doesn't exist, we will create it.
            if (empty($currency)) {
                $currency = new Currency();
                $currency->setCode($item->code);
                $currency->setName($item->name);
                $currency->setSymbol($item->symbol);

                $this->insertCurrency($currency);

                // Add currency to internal collection.
                $this->addCurrency($currency);
            }

            // Add a map between country and currency.
            $this->insertMap('countries_currencies', 'country', 'currency', $country->getId(), $currency->getId());
            $country->addCurrency($currency);
        }
    }

    /**
     * Parse languages from inside a country in a JSON array.
     *
     * @param array $json
     * @param Country $country
     */
    public function parseLanguages(array $json, Country $country)
    {
        foreach ($json as $item) {
            $language = $this->findLanguageByIso6391($item->iso639_1);

            // If the language doesn't exist, we will create it.
            if (empty($language)) {
                $language = new Language();
                $language->setName($item->name);
                $language->setIso6391($item->iso639_1);
                $language->setIso6392($item->iso639_2);
                $language->setNativeName($item->nativeName);

                $this->insertLanguage($language);

                // Add language to internal collection.
                $this->addLanguage($language);
            }

            // Add a map between country and language.
            $this->insertMap('countries_langs', 'country', 'lang', $country->getId(), $language->getId());
            $country->addLanguage($language);
        }
    }

    public function parseTimeZones(array $json, Country $country)
    {
        foreach ($json as $item) {
            $timezone = $this->findTimezoneByName($item);

            // If the timezone doesn't exist, we will create it.
            if (empty($timezone)) {
                $timezone = new TimeZone();
                $timezone->setName($item);

                $this->insertTimeZone($timezone);

                // Add timezone to internal collection.
                $this->addTimeZone($timezone);
            }

            // Add a map between country and timezone.
            $this->insertMap('countries_timezones', 'country', 'timezone', $country->getId(), $timezone->getId());
            $country->addTimezone($timezone);
        }
    }

    /**
     * Insert a country into the database.
     *
     * The country will have its ID set by this method.
     *
     * @param Country $country
     */
    public function insertCountry(Country $country)
    {
        $this->db->query(sprintf(
            "INSERT INTO countries (name, alpha2, alpha3, numeric_code, calling_code, flag_url, region, capital)
                 VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
            $country->getName(),
            $country->getAlpha2(),
            $country->getAlpha3(),
            $country->getNumericCode(),
            $country->getCallingCode(),
            $country->getFlagUrl(),
            $country->getRegion(),
            $country->getCapital()
        ))->execute();

        $country->setId((int) $this->db->getLastInsertId());
    }

    /**
     * Insert a currency into the database.
     *
     * The currency will have its ID set by this method.
     *
     * @param Currency $currency
     */
    public function insertCurrency(Currency $currency)
    {
        $this->db->query(sprintf(
            "INSERT INTO currencies (name, symbol, code)
                 VALUES ('%s', '%s', '%s')",
            $currency->getName(),
            $currency->getSymbol(),
            $currency->getCode()
        ))->execute();

        $currency->setId((int) $this->db->getLastInsertId());
    }

    /**
     * Insert a language into the database.
     *
     * The language will have its ID set by this method.
     *
     * @param Language $language
     */
    public function insertLanguage(Language $language)
    {
        $this->db->query(sprintf(
            "INSERT INTO langs (name, iso639_1, iso639_2, native_name)
                 VALUES ('%s', '%s', '%s', '%s')",
            $language->getName(),
            $language->getIso6391(),
            $language->getIso6392(),
            $language->getNativeName()
        ))->execute();

        $language->setId((int) $this->db->getLastInsertId());
    }

    /**
     * Insert a timezone into the database.
     *
     * The timezone will have its ID set by this method.
     *
     * @param TimeZone $timezone
     */
    public function insertTimeZone(TimeZone $timezone)
    {
        $this->db->query(sprintf(
            "INSERT INTO timezones (name)
                 VALUES ('%s')",
            $timezone->getName()
        ))->execute();

        $timezone->setId((int) $this->db->getLastInsertId());
    }

    /**
     * Insert a row into a mapping table.
     *
     * @param string $table
     * @param string $leftColumn
     * @param string $rightColumn
     * @param int $leftValue
     * @param int $rightValue
     */
    public function insertMap(string $table, string $leftColumn, string $rightColumn, int $leftValue, int $rightValue)
    {
        $this->db->query(sprintf(
            "INSERT INTO %s (%s, %s) VALUES (%d, %d)",
            $table,
            $leftColumn,
            $rightColumn,
            $leftValue,
            $rightValue
        ));
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
