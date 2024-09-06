<?php

namespace Vbergeron\LivewireTables\Traits;

use Livewire\Attributes\Computed;

trait WithPageSize
{
    public int $pageSize = 0;

    public function mountWithPageSize(): void
    {
        $this->pageSize = app('config')->integer('livewire-tables.page-size');
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
