@if($this->hasBulkActions)
    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
            </svg>
        </button>
        <ul class="dropdown-menu">
            @foreach($this->tableBulkActions as $method => $label)
                <li><a class="dropdown-item" wire:click="callBulkAction(@js($method))">{{ $label }}</a></li>
            @endforeach
        </ul>
    </div>
@endif
