<?php

namespace Vbergeron\LivewireTables\Columns\Traits;

trait Sortable
{
    private bool $sortable = false;

    public function sortable(): self
    {
        if (! $this->usableInQueries()) {
            throw new \Exception('You cannot mark a '.class_basename($this).' as a sortable column');
        }

        $this->sortable = true;

        return $this;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }
}
