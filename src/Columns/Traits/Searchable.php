<?php

namespace Vbergeron\LivewireTables\Columns\Traits;

trait Searchable
{
    private bool $searchable = false;

    public function searchable(): self
    {
        if (! $this->usableInQueries()) {
            throw new \Exception('You cannot mark a '.class_basename($this).' as a searchable column');
        }

        $this->searchable = true;

        return $this;
    }

    public function isSearchable(): bool
    {
        return $this->searchable;
    }
}
