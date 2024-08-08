<?php

namespace Vbergeron\LivewireTables\Traits;

use Closure;
use Illuminate\Database\Eloquent\Builder;

trait WithSorting
{
    public ?string $sortField = null;

    public bool $sortReverse = false;

    public function sortBy(string $field): void
    {
        if ($field === $this->sortField) {
            $this->sortReverse = ! $this->sortReverse;
        } else {
            $this->sortField = $field;
            $this->sortReverse = false;
        }

        $this->resetPage();
    }

    private function applySort(Builder $builder, Closure $next): Builder
    {
        if ($this->sortField) {
            $builder->orderBy($this->sortField, $this->sortDirection());
        }

        return $next($builder);
    }

    private function applySortArray(array $items, Closure $next): array
    {
        if ($this->sortField !== null) {
            $this->sortReverse ? arsort($items)
                : asort($items);
        }

        return $next($items);
    }

    private function sortDirection(): string
    {
        return $this->sortReverse ? 'desc' : 'asc';
    }
}
