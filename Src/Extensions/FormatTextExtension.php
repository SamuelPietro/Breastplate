<?php

namespace breastplate\Src\Extensions;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

/**
 * Class FormatTextExtension
 *
 * Extension that provides various text formatting functions for use in Plates templates.
 */
class FormatTextExtension implements ExtensionInterface
{
    /**
     * Register the text formatting function in the Plates engine.
     *
     * @param Engine $engine The Plates engine.
     * @return void
     */
    public function register(Engine $engine): void
    {
        $engine->registerFunction('format_text', [$this, 'formatText']);
    }

    /**
     * Format the given string according to the specified style.
     *
     * @param string $string The string to format.
     * @param string $style The formatting style.
     * @return string The formatted string.
     */
    public function formatText(string $string, string $style): string
    {
        return match ($style) {
            'camelCase' => $this->toCamelCase($string),
            'snake_case' => $this->toSnakeCase($string),
            'kebab-case' => $this->toKebabCase($string),
            'PascalCase' => $this->toPascalCase($string),
            'Train-Case' => $this->toTrainCase($string),
            'lowercase' => $this->toLowerCase($string),
            'Sentence case' => $this->toSentenceCase($string),
            'Initial Case' => $this->toInitialCase($string),

            default => $string,
        };
    }

    /**
     * Convert the string to camelCase.
     *
     * @param string $string The string to convert.
     * @return string The camelCase string.
     */
    public function toCamelCase(string $string): string
    {
        $string = strtolower(trim($string));
        $string = str_replace(' ', '', ucwords($string));
        return lcfirst($string);
    }

    /**
     * Convert the string to snake_case.
     *
     * @param string $string The string to convert.
     * @return string The snake_case string.
     */
    public function toSnakeCase(string $string): string
    {
        $string = strtolower(trim($string));
        return str_replace(' ', '_', $string);
    }

    /**
     * Convert the string to kebab-case.
     *
     * @param string $string The string to convert.
     * @return string The kebab-case string.
     */
    public function toKebabCase(string $string): string
    {
        $string = strtolower(trim($string));
        return str_replace(' ', '-', $string);
    }

    /**
     * Convert the string to PascalCase.
     *
     * @param string $string The string to convert.
     * @return string The PascalCase string.
     */
    public function toPascalCase(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string)));
    }

    /**
     * Convert the string to Train-Case.
     *
     * @param string $string The string to convert.
     * @return string The Train-Case string.
     */
    public function toTrainCase(string $string): string
    {
        $string = strtolower(trim($string));
        $string = str_replace(' ', '-', $string);
        return ucwords($string);
    }

    /**
     * Convert the string to lowercase.
     *
     * @param string $string The string to convert.
     * @return string The lowercase string.
     */
    public function toLowerCase(string $string): string
    {
        return strtolower($string);
    }

    /**
     * Convert the string to Sentence case.
     *
     * @param string $string The string to convert.
     * @return string The Sentence case string.
     */
    public function toSentenceCase(string $string): string
    {
        return ucfirst(strtolower($string));
    }

    /**
     * Convert the string to Initial Case.
     *
     * @param string $string The string to convert.
     * @return string The Initial Case string.
     */
    public function toInitialCase(string $string): string
    {
        return ucwords(strtolower($string));
    }
}
