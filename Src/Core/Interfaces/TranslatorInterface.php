<?php

namespace Breastplate\Src\Core\Interfaces;

interface TranslatorInterface
{
    public function trans(string $key): string;
    public function setLocale(string $locale): void;
}