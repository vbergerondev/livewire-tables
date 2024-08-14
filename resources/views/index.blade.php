@php
/** @var \Vbergeron\LivewireTables\Columns\Column $column */
/** @var \Vbergeron\LivewireTables\Filters\Filter $filter */
@endphp
<div x-data>
    <x-livewire-tables::search />

    @foreach($this->tableFilters as $filter)
        <x-dynamic-component :component="$filter->getBladeComponentName()" :$filter />
    @endforeach

    <div class="my-1">
        <div class="d-flex align-items-center justify-content-between">
            <x-livewire-tables::page-size-selector />
            <small class="d-block text-muted">
                Total: {{ $this->rows->total() }}
            </small>
        </div>
    </div>

    <ul x-data="{ selectedColumns: @js(collect($this->selectedColumns)->pluck('field')) }">
        @foreach($this->tableColumns as $column)
            <li x-data="{ checked: selectedColumns.includes(@js($column->field)) }">
                <input type="checkbox" value="{{ $column->field }}" x-model="selectedColumns">
                <label>{{ $column->name }}</label>
            </li>
        @endforeach

        <button @click="$wire.setSelectedColumns(selectedColumns)">set columns</button>
    </ul>

    <table class="table table-striped">
        <thead>
        <tr>
            @foreach($this->selectedColumns as $column)
                <x-livewire-tables::th :name="$column->name" :field="$column->field" :sortable="$column->isSortable()"/>
            @endforeach
        </tr>
        </thead>

        <tbody>
            @foreach($this->rows as $row)
                <tr>
                    @foreach($this->selectedColumns as $column)
                        <td>{!! $column->getContent($row) !!}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex align-items-center justify-content-between">
        <div>
            {{ $this->rows->links(data: ['scrollTo' => false]) }}
        </div>
    </div>
</div>
