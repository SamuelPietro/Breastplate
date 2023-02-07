<?php

namespace Src\Database;

use PDO;
use PDOException;

class Connection
{
    /**
     * @var PDO|null
     */
    private static $instance = null;

    /**
     * @var PDO
     */
    private PDO $pdo;

    /**
     * Connection constructor.
     *
     * Creates a new PDO instance using the provided database connection details.
     * Throws a PDOException if there's a problem connecting to the database.
     *
     * @throws PDOException
     */
    protected function __construct()
    {
        $host = $_ENV['DB_HOST'];
        $db = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];
        $port = $_ENV['DB_PORT'];
        $charset = $_ENV['DB_CHARSET'];
        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Get a single instance of the PDO object for the database connection.
     *
     * @return PDO
     * @throws PDOException
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}
