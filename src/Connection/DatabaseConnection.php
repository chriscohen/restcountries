<?php
declare(strict_types = 1);

namespace ChrisCohen\Connection;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
use PDOException;
use PDO;
use PDOStatement;

class DatabaseConnection
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $user;

    /**
     * @var string
     */
    protected $pass;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var PDO
     */
    protected $connection;

    public function __construct()
    {
        // Attempt to open the config.yml file and read its database credentials.
        try {
            $yaml = Yaml::parseFile('../config.yml');

            $this->host = $yaml['database']['host'];
            $this->user = $yaml['database']['user'];
            $this->pass = $yaml['database']['pass'];
            $this->name = $yaml['database']['name'];
            $this->port = $yaml['database']['port'];
        } catch (ParseException $e) {
            die($e->getMessage());
        }

        // Attempt to establish and store a database connection.
        try {
            $this->connection = new PDO(
                sprintf("mysql:host=%s;dbname=%s;port=%d", $this->host, $this->name, $this->port),
                $this->user,
                $this->pass
            );
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Run a database query.
     *
     * @param string $sql
     *
     * @return PDOStatement|null
     */
    public function query(string $sql) : ?PDOStatement
    {
         $result = $this->connection->query($sql);

         // Since PDO returns false on failure, we will convert this to null so we can use a nullable method.
         return $result === false ? null : $result;
    }
}
