<?php

namespace Src\Extensions;

use DateTime;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class FormatTimestampExtension implements ExtensionInterface
{
    public function register(Engine $engine): void
    {
        $engine->registerFunction('format_timestamp', [$this, 'formatTimestamp']);
    }

    public function formatTimestamp($timestamp, $format = null): string
    {
        $locale = $this->getCurrentLocale();

        setlocale(LC_TIME, $locale);

        $dateTime = new DateTime();
        $dateTime->setTimestamp($timestamp);

        if (empty($format)) {
            $format = 'd/m/Y H:i:s';
        }

        return $dateTime->format($format);
    }

    public function getCurrentLocale(): false|array|string
    {
        return $_ENV['LANG'];
    }
}
