@php
/** @var \Vbergeron\LivewireTables\Filters\SelectFilter $filter */
@endphp
@props(['filter'])
<div>
    <label for="">{{$filter->getName()}}</label>
    <select {{ $filter->isMultiple() ? 'multiple' : '' }} class="form-control" wire:model.live="filters.{{$filter->getField()}}">
        <option value="null" selected>Choose an option</option>
        @foreach($filter->getOptions() as $k=>$v)
            <option value="{{$k}}">{{$v}}</option>
        @endforeach
    </select>
</div>
