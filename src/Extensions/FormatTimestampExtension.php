<?php

namespace Src\Extensions;

use DateTime;

class FormatTimestampExtension
{

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
        return getenv('LANG');
    }
}
