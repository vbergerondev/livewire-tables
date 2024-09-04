<?php

namespace Vbergeron\LivewireTables\Traits;

use Vbergeron\LivewireTables\Columns\Column;

trait WithSelectableColumns
{
    public array $selectedColumns = [];

    public function mountWithSelectableColumns(): void
    {
        $this->selectedColumns = cache()->get('columns') ?? array_map(fn($c) => $c->field, $this->tableColumns);
    }

    public function updatedSelectedColumns(): void
    {
        cache()->put('columns', $this->selectedColumns, now()->addYear());
    }

    public function isColumnSelected(Column $column): bool
    {
        return in_array($column->field, $this->selectedColumns);
    }
}
