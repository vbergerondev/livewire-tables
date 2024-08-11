<?php

namespace Vbergeron\LivewireTables\Filters;

use Closure;

abstract class Filter
{
    private ?Closure $callback = null;

    public function __construct(private readonly string $name, private readonly string $field) {}

    public function using(Closure $callback): self
    {
        $this->callback = $callback;

        return $this;
    }

    public function getBladeComponentName(): string
    {
        return 'livewire-tables::filters.'.str(class_basename($this))->kebab();
    }

    public function getField(): string
    {
        return str($this->field)->replace('.', '_');
    }

    public function getCallback(): ?Closure
    {
        return $this->callback;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
