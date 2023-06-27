<?php

namespace Src\Database;

use PDO;

/**
 * An interface for creating and managing database connections.
 */
interface ConnectionInterface
{
    /**
     * Get a PDO instance for the database connection.
     *
     * @return PDO The PDO instance
     */
    public function connect(): PDO;

    /**
     * Close the PDO connection.
     */
    public function disconnect(): void;
}
