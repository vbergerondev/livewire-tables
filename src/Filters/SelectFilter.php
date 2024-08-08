<?php

namespace Vbergeron\LivewireTables\Filters;

class SelectFilter extends Filter
{
    private array $options;

    public function withOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
