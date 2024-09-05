<?php

namespace Vbergeron\LivewireTables\Contracts;

interface SelectedColumnsStorageInterface
{
    public function get(string $key, array $default): array;

    public function set(string $key, array $values = []): void;
}
