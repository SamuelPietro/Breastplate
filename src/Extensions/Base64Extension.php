<?php

namespace Src\Extensions;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class Base64Extension implements ExtensionInterface
{
    public function register(Engine $engine): void
    {
        $engine->registerFunction('base64_encode', [$this, 'base64Encode']);
        $engine->registerFunction('base64_decode', [$this, 'base64Decode']);
    }

    public function base64Encode($blob): string
    {
        return base64_encode($blob);
    }

    public function base64Decode($blob): string
    {
        return base64_decode($blob);
    }
}