<?php

namespace Vbergeron\LivewireTables\Traits;

use Livewire\Attributes\Computed;
use TypeError;

trait WithPageSize
{
    public int $pageSize = 0;

    public function mountWithPageSize(): void
    {
        $pageSize = config('livewire-tables.page-size');

        if (! is_int($pageSize)) {
            throw new TypeError('Variable is not an [int]');
        }
        $this->pageSize = $pageSize;
    }

    public function updatedPageSize(): void
    {
        $this->resetPage();
    }

    /**
     * @return int[]
     */
    #[Computed]
    public function pageSizes(): array
    {
        return [10, 20, 25, 50, 75, 100];
    }
}
