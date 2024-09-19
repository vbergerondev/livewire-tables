<button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#exampleModalCols">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
    </svg>
</button>

@teleport('body')
    <div wire:ignore.self x-data="{ selectedColumns: @entangle('selectedColumns') }" class="modal modal-lg fade" id="exampleModalCols" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Columns</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        @foreach($this->tableColumns as $c)
                            <li class="list-group-item">
                                <input class="form-check-input me-1" type="checkbox" x-model="selectedColumns" value="{{ $c->field }}" id="{{ $c->field }}">
                                <label class="form-check-label stretched-link" for="{{ $c->field }}">
                                    {{ $c->name }}
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" x-ref="close" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="$wire.void(); $refs.close.click()">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endteleport
