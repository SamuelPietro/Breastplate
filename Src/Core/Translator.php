<?php

namespace Breastplate\Src\Core;

use Breastplate\Src\Core\Interfaces\TranslatorInterface;

class Translator implements TranslatorInterface
{
    private string $locale;
    private array $translations = [];

    public function __construct(string $locale = 'en')
    {
        $this->locale = $locale;
        $this->loadTranslations();
    }

    private function loadTranslations(): void
    {
        $file = __DIR__ . '/../../resources/lang/' . $this->locale . '.php';
        if (file_exists($file)) {
            $this->translations = require $file;
        }
    }

    public function trans(string $key): string
    {
        return $this->translations[$key] ?? $key;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
        $this->loadTranslations();
    }
}
