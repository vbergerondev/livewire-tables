<?php

namespace Vbergeron\LivewireTables\Traits;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Url;
use Vbergeron\LivewireTables\Filters\Filter;

trait WithFiltering
{
    #[Url(except: '')]
    public array $filters = [];

    public function setFilters(): void
    {
        $this->dispatch('filters-applied');
    }

    private function applyFilters(Builder $builder, Closure $next): Builder
    {
        foreach ($this->filters as $k => $v) {
            /** @var Filter|null $filter */
            $filter = collect($this->tableFilters)
                ->firstWhere(fn (Filter $filter) => $filter->getField() === $k);

            if (! $filter instanceof Filter) {
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
        $items = array_filter($items, function (Model $item): bool {
            return collect($this->filters)
                ->every(function (mixed $value, string $key) use ($item): bool {
                    $key = (string) str($key)->replace('_', '.');

                    if (is_array($value)) {
                        return in_array($item->$key, $value);
                    }

                    return $value == $item->$key;
                });
        });

        return $next($items);
    }
}
