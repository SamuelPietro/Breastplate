<?php

namespace Src\Extensions;

class FormatTextExtension
{

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

    public function toCamelCase(string $string): string
    {
        $string = strtolower(trim($string));
        $string = str_replace(' ', '', ucwords($string));
        return lcfirst($string);
    }

    public function toSnakeCase(string $string): string
    {
        $string = strtolower(trim($string));
        return str_replace(' ', '_', $string);
    }

    public function toKebabCase(string $string): string
    {
        $string = strtolower(trim($string));
        return str_replace(' ', '-', $string);
    }

    public function toPascalCase(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string)));
    }

    public function toTrainCase(string $string): string
    {
        $string = strtolower(trim($string));
        $string = str_replace(' ', '-', $string);
        return ucwords($string);
    }

    public function toLowerCase(string $string): string
    {
        return strtolower($string);
    }

    public function toSentenceCase(string $string): string
    {
        return ucfirst(strtolower($string));
    }

    public function toInitialCase(string $string): string
    {
        return ucwords(strtolower($string));
    }
}
