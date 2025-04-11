<?php

namespace Breastplate\Src\Core\Interfaces;

/**
 * TranslatorInterface interface
 * 
 * This interface defines the methods for a translator.
 * 
 * 
 */
interface TranslatorInterface
{
    /**
     * Translate a message.
     * 
     * @param string $key The key to translate.
     * @return string The translated message.
     */
    public function trans(string $key): string;

    /**
     * Set the locale.
     * 
     * @param string $locale The locale to use.
     * @return void
     */
    public function setLocale(string $locale): void;
}
