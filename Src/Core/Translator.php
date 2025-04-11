<?php

namespace Breastplate\Src\Core;

use Breastplate\Src\Core\Interfaces\TranslatorInterface;

/**
 * Translator class
 * 
 * This class is responsible for translating messages.
 * 
 * 
 */
class Translator implements TranslatorInterface
{
    private string $locale;
    private array $translations = [];

    /**
     * Constructor.
     * 
     * @param string $locale The locale to use.
     * @return void
     */
    public function __construct(string $locale = 'en')
    {
        $this->locale = $locale;
        $this->loadTranslations();
    }

    /**
     * Load translations from the language file.
     * 
     * @return void
     */
    private function loadTranslations(): void
    {
        $file = __DIR__ . '/../../resources/lang/' . $this->locale . '.php';
        if (file_exists($file)) {
            $this->translations = require $file;
        }
    }

    /**
     * Translate a message.
     * 
     * @param string $key The key to translate.
     * @return string The translated message.
     */
    public function trans(string $key): string
    {
        return $this->translations[$key] ?? $key;
    }

    /**
     * Set the locale.
     * 
     * @param string $locale The locale to use. 
     * @return void
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
        $this->loadTranslations();
    }
}
