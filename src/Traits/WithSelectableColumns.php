<?php

namespace Vbergeron\LivewireTables\Traits;

use Vbergeron\LivewireTables\Columns\Column;
use Vbergeron\LivewireTables\Contracts\SelectedColumnsStorageInterface;

trait WithSelectableColumns
{
    /**
     * @var string[]
     */
    public array $selectedColumns = [];

    public function mountWithSelectableColumns(SelectedColumnsStorageInterface $columnsStorage): void
    {
        $this->selectedColumns = $columnsStorage->get(
            key: sprintf('livewire-tables.%s.selected-columns', $this->tableName),
            default: array_map(fn (Column $column): string => $column->field, $this->tableColumns),
        );
    }

    public function updatedSelectedColumns(SelectedColumnsStorageInterface $columnsStorage): void
    {
        $columnsStorage->set(
            key: sprintf('livewire-tables.%s.selected-columns', $this->tableName),
            values: $this->selectedColumns,
        );
    }

    public function isColumnSelected(Column $column): bool
    {
        return in_array($column->field, $this->selectedColumns);
    }
}
