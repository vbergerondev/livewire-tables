<?php

namespace Vbergeron\LivewireTables\Traits;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Url;
use Vbergeron\LivewireTables\Columns\Column;

trait WithSearching
{
    #[Url(as: 'q', except: '')]
    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    private function applySearch(Builder $builder, Closure $next): Builder
    {
        if ($this->search === '') {
            return $next($builder);
        }

        $this->search = (string) str($this->search)->squish();

        $fields = collect($this->tableColumns)
            ->filter(fn (Column $column): bool => $column->isSearchable())
            ->map(function (Column $column, int $index) use ($builder) {
                $table = $builder->getModel()->getTable();

                return $column->isBaseField() ? "$table.$column->field" : $column->field;
            })
            ->all();

        $builder->whereAny($fields, 'LIKE', "%{$this->search}%");

        return $next($builder);
    }

    private function applySearchArray(array $items, Closure $next): array
    {
        if ($this->search === '') {
            return $next($items);
        }

        $items = array_filter($items, $this->filterArrayItems(...));

        return $next($items);
    }

    private function filterArrayItems(Model $item): bool
    {
        $fields = collect($this->tableColumns)
            ->filter(fn (Column $column): bool => $column->isSearchable())
            ->pluck('field');

        foreach ($fields as $field) {
            if (str_contains(strtolower($item[$field] ?? ''), strtolower($this->search))) {
                return true;
            }
        }

        return false;
    }
}
