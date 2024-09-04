<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalCols">
    Cols
</button>

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
