<?php

namespace Vbergeron\LivewireTables\Traits;

use Livewire\Attributes\Computed;

trait WithPageSize
{
    public int $pageSize = 0;

    public function bootWithPageSize(): void
    {
        $this->pageSize = config('livewire-datatable.page-size');
    }

    public function updatedPageSize(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function pageSizes(): array
    {
        return [10, 20, 25, 50, 75, 100];
    }
}
