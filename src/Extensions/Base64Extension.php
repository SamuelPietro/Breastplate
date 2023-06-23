<?php

namespace Src\Extensions;

class Base64Extension
{

    public function base64Encode($blob): string
    {
        return base64_encode($blob);
    }

    public function base64Decode($blob): string
    {
        return base64_decode($blob);
    }
}