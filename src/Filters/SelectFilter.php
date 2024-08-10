<?php

namespace Vbergeron\LivewireTables\Filters;

use Closure;

class SelectFilter
{
    private array $options = [];

    public bool $multiple = false;

    public ?Closure $callback = null;

    public function __construct(private string $name, private string $field) {}

    public function withOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function using(Closure $callback): self
    {
        $this->callback = $callback;

        return $this;
    }

    public function getBladeComponentName(): string
    {
        return 'livewire-tables::filters.select-filter';
    }

    public function multiple(): self
    {
        $this->multiple = true;

        return $this;
    }

    public function getField(): string
    {
        return str($this->field)->replace('.', '_');
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    public function getCallback(): ?Closure
    {
        return $this->callback;
    }
}
