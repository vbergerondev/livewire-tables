<?php

namespace Vbergeron\LivewireTables\Columns\Traits;

trait Searchable
{
    private bool $searchable = false;

    /**
     * @throws \Exception
     */
    public function searchable(): self
    {
        if (! $this->isQueryable()) {
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
