@php
    /** @var \Vbergeron\LivewireTables\Filters\Filter $filter */
@endphp

@props(['filter'])

<select wire:model.live="filters.{{$filter->getField()}}">
    @foreach($filter->getOptions() as $k=>$v)
        <option value="{{ $k }}">{{ $v }}</option>
    @endforeach
</select>
