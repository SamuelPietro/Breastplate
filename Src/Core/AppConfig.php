<?php

namespace Breastplate\Src\Core;

use Exception;
use Breastplate\Config\Config;

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
    private array $config;

    /**
     * AppConfig constructor.
     *
     * @throws Exception If a required configuration is missing or invalid.
     */
    public function __construct()
    {
        $config = new Config();
        $this->config = $config->get();
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
        $this->validateRequiredConfig('APP_NAME');
        $this->validateRequiredConfig('BASE_URL');
        $this->validateRequiredConfig('DB_HOST');
        $this->validateRequiredConfig('DB_NAME');
        $this->validateRequiredConfig('DB_USER');
        $this->validateRequiredConfig('DB_PASS');
    }

    /**
     * Validate a required configuration setting.
     *
     * @param string $key The configuration key.
     * @throws Exception If the configuration is missing or empty.
     */
    private function validateRequiredConfig(string $key): void
    {
        if (!isset($this->config[$key]) || empty($this->config[$key])) {
            $message = "Missing or empty required configuration: $key";
            error_log($message);
            throw new Exception($message);
        }
    }
}