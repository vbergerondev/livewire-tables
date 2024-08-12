<?php

namespace Vbergeron\LivewireTables\Traits;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Locked;

trait WithSorting
{
    #[Locked]
    public ?string $sortField = null;

    #[Locked]
    public bool $sortReverse = false;

    public function sortBy(string $field, ?bool $sortReverse = null): void
    {
        $sortReverse ??= $this->sortReverse;

        if ($field === $this->sortField) {
            $this->sortReverse = ! $this->sortReverse;
        } else {
            $this->sortField = $field;
            $this->sortReverse = $sortReverse;
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
        if ($this->sortField === null) {
            return $next($items);
        }

        if ($this->sortReverse) {
            usort($items, function (Model $a, Model $b) {
                return $a[$this->sortField] <=> $b[$this->sortField];
            });
        } else {
            usort($items, function (Model $a, Model $b) {
                return $b[$this->sortField] <=> $a[$this->sortField];
            });
        }

        return $next($items);
    }

    private function sortDirection(): string
    {
        return $this->sortReverse ? 'desc' : 'asc';
    }
}
