<?php

namespace Vbergeron\LivewireTables\Filters;

abstract class Filter
{
    public function __construct(private string $name, private string $field) {}

    public function getBladeComponentName(): string
    {
        return str(class_basename($this))->kebab();
    }

    public function getField(): string
    {
        return $this->field;
    }
}
