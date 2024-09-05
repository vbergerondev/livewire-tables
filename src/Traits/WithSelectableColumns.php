<?php

namespace Vbergeron\LivewireTables\Traits;

use Illuminate\Support\Facades\Cache;
use TypeError;
use Vbergeron\LivewireTables\Columns\Column;

trait WithSelectableColumns
{
    /**
     * @var string[]
     */
    public array $selectedColumns = [];

    public function mountWithSelectableColumns(): void
    {
        $columns = Cache::get('columns') ?? array_map(fn (Column $column): string => $column->field, $this->tableColumns);

        if (! is_array($columns)) {
            throw new TypeError('Variable is not an [array]');
        }

        $this->selectedColumns = $columns;
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
