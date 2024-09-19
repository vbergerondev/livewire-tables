@if($this->hasBulkActions)
    @if($this->hasBulkActions)
        <div x-data="{ selectedAction: null }" class="form-group d-flex gap-1">
            <select x-model="selectedAction" class="form-control">
                @foreach($this->tableBulkActions as $method => $label)
                    <option selected :disabled="true">Choose an action</option>
                    <option value="{{ $method }}">{{ $label }}</option>
                @endforeach
            </select>
            <button
                @click="$wire.callBulkAction(selectedAction); selectedAction = null"
                class="btn btn-outline-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                </svg>
            </button>
        </div>
    @endif
@endif
