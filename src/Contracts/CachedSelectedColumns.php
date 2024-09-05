<?php

namespace Vbergeron\LivewireTables\Contracts;

use Illuminate\Support\Facades\Cache;
use TypeError;

class CachedSelectedColumns implements SelectedColumnsStorageInterface
{
    public function get(string $key, array $default = []): array
    {
        $columns = Cache::get(
            key: $key,
            default: $default,
        );

        if (! is_array($columns)) {
            throw new TypeError('Variable is not an [array]');
        }

        return $columns;
    }

    public function set(string $key, array $values = []): void
    {
        Cache::put($key, $values, now()->addYear());
    }
}
