<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Filters
</button>

<div x-data class="modal modal-lg fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Filters</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @foreach($this->tableFilters as $filter)
                    <x-dynamic-component :component="$filter->getBladeComponentName()" :$filter />
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" x-ref="close" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="$wire.void(); $refs.close.click()">Save changes</button>
            </div>
        </div>
    </div>
</div>
