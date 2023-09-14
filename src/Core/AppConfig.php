<?php

namespace Src\Core;

use Exception;

/**
 * Class AppConfig
 *
 * A class for storing application configurations.
 */
class AppConfig
{
    /**
     * @var array The configuration settings.
     */
    private array $config = [];

    /**
     * AppConfig constructor.
     *
     * Loads the configuration settings.
     * @throws Exception
     */
    public function __construct()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];

        $this->config = [
            # The name of the application
            'APP_NAME' => 'PFrame',

            # A description of the application
            'APP_DESC' => 'Um framework PHP usando o padrão MVC',

            # Keywords or tags associated with the application
            'APP_KEYS' => 'mvc, php, framework',

            # The author or creator of the application
            'APP_AUTHOR' => 'Samuel Pietro',

            # Flag indicating whether debugging is enabled
            'DEBUG' => true,

            # The language setting for the application
            'LANG' => 'en.UTF-8',

            # The language preference for the application
            'APP_LANGUAGE' => 'english',

            # The base URL of the application
            'BASE_URL' => $protocol . '://' . $host,

            # Database Configuration

            # The hostname or IP address of the database server
            'DB_HOST' => 'localhost',

            # The name of the database to connect to
            'DB_NAME' => 'pframe',

            # The username for the database connection
            'DB_USER' => 'root',

            # The password for the database connection
            'DB_PASS' => 'root',

            # The port number for the database connection
            'DB_PORT' => 3306,

            # The character set for the database connection
            'DB_CHARSET' => 'utf8mb4',

            # The path to the SSL CA file for secure database connections
            'DB_SSL_CA_FILE' => '/resources/ssl/database.crt.pem',

            # Flag indicating whether to verify the database server's SSL certificate
            'DB_VERIFY_CERT' => false,
        ];

        // Validação das configurações
        $this->validateConfig();
    }

    /**
     * Get a configuration value by key.
     *
     * @param string $key     The configuration key.
     * @param mixed|null $default The default value to return if the key is not found.
     *
     * @return mixed The configuration value or the default value if not found.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Check if debugging is enabled.
     *
     * @return bool True if debugging is enabled, false otherwise.
     */
    public function isDebug(): bool
    {
        return (bool)$this->config['DEBUG'];
    }

    /**
     * Get the database configuration settings.
     *
     * @return array The database configuration settings.
     */
    public function getDbConfig(): array
    {
        return [
            'host' => $this->config['DB_HOST'],
            'name' => $this->config['DB_NAME'],
            'user' => $this->config['DB_USER'],
            'pass' => $this->config['DB_PASS'],
            'port' => (int)$this->config['DB_PORT'],
            'charset' => $this->config['DB_CHARSET'],
            'ssl_ca_file' => $this->config['DB_SSL_CA_FILE'],
            'verify_cert' => (bool)$this->config['DB_VERIFY_CERT'],
        ];
    }

    /**
     * Validate the configuration settings.
     *
     * @throws Exception If a required configuration is missing or invalid.
     */
    private function validateConfig(): void
    {
        $requiredConfig = ['APP_NAME', 'BASE_URL', 'DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS'];
        foreach ($requiredConfig as $key) {
            if (!isset($this->config[$key]) || empty($this->config[$key])) {
                $message = "Missing or empty required configuration: $key";
                error_log($message);
                throw new Exception($message);
            }
        }
    }
}
