@php
/** @var \Vbergeron\LivewireTables\Columns\Column $column */
/** @var \Vbergeron\LivewireTables\Filters\Filter $filter */
/** @var \Vbergeron\LivewireTables\LivewireTables $this */
@endphp
<div x-data x-cloak>

    <div class="row">
        <div class="col-md-9">
            <x-livewire-tables::search />
        </div>
        <div class="col-md-1">
            <x-livewire-tables::filters />
        </div>

        <div class="col-md-1">
            <x-livewire-tables::columns-selector />
        </div>
    </div>

    <x-livewire-tables::bulk-actions />

    <div class="my-1">
        <div class="d-flex align-items-center justify-content-between">
            <x-livewire-tables::page-size-selector />
            <small class="d-block text-muted">
                Total: {{ $this->rows->total() }}
            </small>
        </div>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                @if($this->hasBulkActions)
                    <th style="width: 50px;">
                        <input type="checkbox" class="form-check-input">
                    </th>
                @endif
                @foreach($this->tableColumns as $column)
                    @continue(! $this->isColumnSelected($column))
                    <x-livewire-tables::th :field="$column->field" :sortable="$column->isSortable()">
                        {{ $column->name }}
                    </x-livewire-tables::th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @if(count($selectedRows) > 0)
                <tr class="table-info">
                    <td colspan="{{ count($this->selectedColumns) + 1 }}">
                        Rows selected: {{ count($selectedRows) }}
                    </td>
                </tr>
            @endif
            @foreach($this->rows as $row)
                <tr>
                    @if($this->hasBulkActions)
                        <td style="width: 50px;">
                            <input value="{{ $row[$this->primaryKey] }}"
                                   type="checkbox"
                                   class="form-check-input"
                                   wire:model.live="selectedRows">
                        </td>
                    @endif
                    @foreach($this->tableColumns as $column)
                        @continue(! $this->isColumnSelected($column))
                        <td>{!! $column->getContent($row) !!}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex align-items-center justify-content-end">
        <div>
            {{ $this->rows->links(data: ['scrollTo' => false]) }}
        </div>
    </div>
</div>
