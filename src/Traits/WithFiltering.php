<?php

namespace Vbergeron\LivewireTables\Traits;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Vbergeron\LivewireTables\Filters\Filter;

trait WithFiltering
{
    #[Url]
    public array $filters = [];

    public function bootWithFiltering(): void
    {
        $this->removeUnauthorizedFilters();
    }

    private function applyFilters(Builder $builder, Closure $next): Builder
    {
        return $next($builder);
    }

    private function removeUnauthorizedFilters(): void
    {
        foreach (array_keys($this->filters) as $key) {
            if (! in_array($key, array_map(fn (Filter $filter): string => $filter->getField(), $this->filters()))) {
                unset($this->filters[$key]);
            }
        }
    }
}
