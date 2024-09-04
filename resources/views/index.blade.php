@php
/** @var \Vbergeron\LivewireTables\Columns\Column $column */
/** @var \Vbergeron\LivewireTables\Filters\Filter $filter */
@endphp
<div x-data>

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
            @foreach($this->tableColumns as $column)
                @continue(! $this->isColumnSelected($column))
                <x-livewire-tables::th :name="$column->name" :field="$column->field" :sortable="$column->isSortable()"/>
            @endforeach
        </tr>
        </thead>

        <tbody>
            @foreach($this->rows as $row)
                <tr>
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
            {{ $this->rows->links(data: ['scrollTo' => false]) }}
        </div>
    </div>
</div>
