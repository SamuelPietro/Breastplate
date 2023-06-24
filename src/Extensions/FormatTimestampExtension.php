<?php

namespace Src\Extensions;

use DateTime;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

/**
 * Class FormatTimestampExtension
 *
 * Extension that provides a function to format timestamps in different date and time formats.
 */
class FormatTimestampExtension implements ExtensionInterface
{
    /**
     * Register the timestamp formatting function in the Plates engine.
     *
     * @param Engine $engine The Plates engine.
     * @return void
     */
    public function register(Engine $engine): void
    {
        $engine->registerFunction('format_timestamp', [$this, 'formatTimestamp']);
    }

    /**
     * Format the given timestamp according to the specified format.
     *
     * @param mixed $timestamp The timestamp to format.
     * @param string|null $format The date and time format (optional).
     * @return string The formatted timestamp.
     */
    public function formatTimestamp($timestamp, $format = null): string
    {
        $locale = $this->getCurrentLocale();

        setlocale(LC_TIME, $locale);

        $dateTime = new DateTime();
        $dateTime->setTimestamp($timestamp);

        if (empty($format)) {
            $format = 'd/m/Y H:i:s'; // BR Format
        }

        return $dateTime->format($format);
    }

    /**
     * Get the current locale.
     *
     * @return false|array|string The current locale.
     */
    public function getCurrentLocale(): false|array|string
    {
        return $_ENV['LANG'];
    }
}
