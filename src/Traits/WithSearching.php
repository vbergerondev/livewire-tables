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

        collect($this->columns())
            ->filter(fn (Column $column): bool => $column->isSearchable())
            ->each(function (Column $column, int $index) use ($builder) {
                $table = $builder->getModel()->getTable();
                $field = $column->isBaseField() ? "$table.$column->field" : $column->field;

                if ($index === 0) {
                    $builder->where($field, 'like', "%$this->search%");
                } else {
                    $builder->orWhere($field, 'like', "%$this->search%");
                }
            });

        return $next($builder);
    }

    public function applySearchArray(array $items, Closure $next): array
    {
        if ($this->search === '') {
            return $next($items);
        }

        $items = array_filter($items, function (Model $item): bool {
            $fields = collect($this->columns())
                ->filter(fn (Column $column): bool => $column->isSearchable())
                ->pluck('field');

            foreach ($fields as $field) {
                if (str_contains(strtolower(data_get($item, $field)), strtolower($this->search))) {
                    return true;
                }
            }

            return false;
        });

        return $next($items);
    }
}
