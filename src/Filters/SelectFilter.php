<?php

namespace Vbergeron\LivewireTables\Filters;

class SelectFilter
{
    private array $options = [];

    public function __construct(private string $name, private string $field) {}

    public function withOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getBladeComponentName(): string
    {
        return 'livewire-tables::filters.select-filter';
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
