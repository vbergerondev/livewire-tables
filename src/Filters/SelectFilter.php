<?php

namespace Vbergeron\LivewireTables\Filters;

final class SelectFilter extends Filter
{
    /** @var array<mixed, mixed> */
    private array $options = [];

    private bool $multiple = false;

    /**
     * @param  array<mixed, mixed>  $options
     */
    public function withOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function multiple(): self
    {
        $this->multiple = true;

        return $this;
    }

    public function isMultiple(): bool
    {
        return $this->multiple;
    }
}
