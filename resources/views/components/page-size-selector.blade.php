<div>
    <label for="per-page">{{ __('livewire-datatable::page-size') }}</label>
    <select id="per-page" class="form-control">
        @foreach($this->pageSizes as $size)
            <option wire:click="$set('pageSize', @js($size))" :selected="$wire.pageSize === @js($size)">{{ $size }}</option>
        @endforeach
    </select>
</div>
