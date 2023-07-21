<?php

namespace System\Kernel;

interface StorageInterface
{
    public function add(string $key, mixed $value): void;

    public function get(string $key, mixed $default = null): mixed;

    public function isset(string $key): bool;

    public function delete(string $key): void;

    public function clear(): void;
}