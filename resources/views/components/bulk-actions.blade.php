@if($this->hasBulkActions)
    <div class="form-group">
        <select wire:input="callBulkAction(event.target.value)" class="form-control">
            @foreach($this->tableBulkActions as $method => $label)
                <option selected disabled>Choose an action</option>
                <option value="{{ $method }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>
@endif
