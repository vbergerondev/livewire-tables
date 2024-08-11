<div class="input-group input-group-merge">
    <span class="input-group-text"><i class="bx bx-search"></i></span>
    <input type="text" wire:model.live.debounce.500ms="search" class="form-control" placeholder="{{ __('Search') }}" aria-label="Search">
</div>
