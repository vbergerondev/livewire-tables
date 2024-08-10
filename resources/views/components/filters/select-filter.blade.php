@props(['filter'])
<div>
    <select wire:model.live="filters.{{$filter->getField()}}">
        <option value="" selected>Choose an option</option>
        @foreach($filter->getOptions() as $k=>$v)
            <option value="{{$k}}">{{$v}}</option>
        @endforeach
    </select>
</div>
