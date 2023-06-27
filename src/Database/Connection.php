<?php

namespace Src\Database;

use PDO;
use PDOException;
use SplQueue;

/**
 * A class for managing a connection pool for database connections.
 */
class Connection implements ConnectionInterface
{
    /**
     * The maximum number of connections allowed in the pool.
     *
     * @var int
     */
    private int $maxConnections;

    /**
     * The queue of available connections.
     *
     * @var SplQueue
     */
    private SplQueue $availableConnections;

    /**
     * The PDO connection options.
     *
     * @var array
     */
    private array $pdoOptions;

    /**
     * ConnectionPool constructor.
     *
     * @param int $maxConnections The maximum number of connections allowed in the pool
     */
    public function __construct(int $maxConnections = 10)
    {
        $this->maxConnections = $maxConnections;
        $this->pdoOptions = [
            'mysql:host=' . $_ENV['DB_HOST'] . ';port=' . $_ENV['DB_PORT'] . ';dbname=' . $_ENV['DB_NAME'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASS'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ],
        ];
        $this->availableConnections = new SplQueue();
    }

    /**
     * Get a PDO instance from the connection pool.
     *
     * @return PDO The PDO instance
     * @throws PDOException If an error occurs while getting a connection
     */
    public function getConnection(): PDO
    {
        if (!$this->availableConnections->isEmpty()) {
            return $this->availableConnections->dequeue();
        }

        if ($this->getTotalConnections() < $this->maxConnections) {
            return $this->createConnection();
        }

        throw new PDOException('Connection pool is full. Unable to get a connection.');
    }

    /**
     * Release a PDO instance back to the connection pool.
     *
     * @param PDO $connection The PDO instance to release
     */
    public function releaseConnection(PDO $connection): void
    {
        $this->availableConnections->enqueue($connection);
    }

    /**
     * Close all connections in the connection pool.
     */
    public function closeConnections(): void
    {
        while (!$this->availableConnections->isEmpty()) {
            $connection = $this->availableConnections->dequeue();
            $connection = null;
        }
    }

    /**
     * Get the total number of connections in the connection pool.
     *
     * @return int The total number of connections
     */
    public function getTotalConnections(): int
    {
        return $this->availableConnections->count();
    }

    /**
     * Create a new PDO connection.
     *
     * @return PDO The new PDO connection
     * @throws PDOException If an error occurs while creating a connection
     */
    private function createConnection(): PDO
    {
        try {
            $pdo = new PDO(...$this->pdoOptions);
            return $pdo;
        } catch (PDOException $exception) {
            throw new PDOException('Failed to create a new database connection.', 0, $exception);
        }
    }

    /**
     * Get a PDO instance for the database connection.
     *
     * @return PDO The PDO instance
     * @throws PDOException If an error occurs while connecting
     */
    public function connect(): PDO
    {
        return $this->getConnection();
    }

    /**
     * Close the PDO connection.
     */
    public function disconnect(): void
    {
        $this->closeConnections();
    }
}
