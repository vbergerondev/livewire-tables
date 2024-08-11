<?php

namespace Vbergeron\LivewireTables\Traits;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Vbergeron\LivewireTables\Filters\SelectFilter;

trait WithFiltering
{
    #[Url(except: '')]
    public array $filters = [];

    private function applyFilters(Builder $builder, Closure $next): Builder
    {
        foreach ($this->tableFilters as $k => $v) {
            /** @var SelectFilter $filter */
            $filter = collect($this->tableFilters)
                ->firstWhere(fn (SelectFilter $filter) => $filter->getField() === $k);

            if (! $filter instanceof SelectFilter) {
                continue;
            }

            if ($filter->getCallback() instanceof Closure) {
                call_user_func_array($filter->getCallback(), [$builder, $filter, $v]);

                continue;
            }

            if ($filter->isMultiple()) {
                $builder->whereIn($filter->getField(), $v);

                continue;
            }

            $builder->where($filter->getField(), $v);
        }

        return $next($builder);
    }

    private function applyFiltersArray(array $items, Closure $next): array
    {
        // @TODO

        return $next($items);
    }
}
