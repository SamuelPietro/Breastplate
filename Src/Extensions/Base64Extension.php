<?php

namespace Breastplate\Src\Extensions;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

/**
 * Class Base64Extension
 *
 * Extension that provides base64 encoding and decoding functions for use in Plates templates.
 */
class Base64Extension implements ExtensionInterface
{
    /**
     * Register the base64 encoding and decoding functions in the Plates engine.
     *
     * @param Engine $engine The Plates engine.
     * @return void
     */
    public function register(Engine $engine): void
    {
        $engine->registerFunction('base64_encode', [$this, 'base64Encode']);
        $engine->registerFunction('base64_decode', [$this, 'base64Decode']);
    }

    /**
     * Base64 encodes the given blob of data.
     *
     * @param mixed $blob The data to encode.
     * @return string The base64-encoded data.
     */
    public function base64Encode($blob): string
    {
        return base64_encode($blob);
    }

    /**
     * Base64 decodes the given blob of data.
     *
     * @param string $blob The base64-encoded data.
     * @return string The decoded data.
     */
    public function base64Decode(string $blob): string
    {
        return base64_decode($blob);
    }
}
