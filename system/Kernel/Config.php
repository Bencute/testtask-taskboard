<?php

namespace System\Kernel;

class Config
{
    private function __construct() {}

    public static function get(): array
    {
        return require __DIR__ . '/../../config/main.php';
    }
}