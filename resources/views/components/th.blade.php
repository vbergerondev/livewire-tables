@props(['sortable' => false, 'name', 'field'])

<th
    @if($sortable) role="button" wire:click="sortBy(@js($field))" @endif
>{{ $name }}
</th>
