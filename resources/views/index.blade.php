@php
/** @var \Vbergeron\LivewireTables\Columns\Column $column */
/** @var \Vbergeron\LivewireTables\Filters\Filter $filter */
/** @var \Vbergeron\LivewireTables\LivewireTables $this */
@endphp
<div x-data x-cloak>

    <div class="d-flex justify-content-between">
        <div class="w-50">
            <x-livewire-tables::search />
        </div>
        <div>
            <div class="d-flex align-items-center gap-2 gap mb-2">
                <x-livewire-tables::toolbar.filters />
                <x-livewire-tables::toolbar.columns />
                <x-livewire-tables::toolbar.bookmarks />

                @if($this->isExportable)
                    <x-livewire-tables::toolbar.export />
                @endif
            </div>
        </div>
    </div>


    <div class="my-1 d-flex justify-content-between align-items-center">
        <div class="w-25">
            <x-livewire-tables::page-size-selector />
        </div>
        <div>
            <x-livewire-tables::toolbar.bulk-actions />
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

    <div class="d-flex align-items-center justify-content-between">
        <div>
            <small class="d-block text-muted">
                Total records: {{ $this->rows->total() }}
            </small>
        </div>
        <div>
            {{ $this->rows->links(data: ['scrollTo' => false]) }}
        </div>
    </div>
</div>
