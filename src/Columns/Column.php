<?php

namespace Vbergeron\LivewireTables\Columns;

use Closure;
use Illuminate\Database\Eloquent\Model;

abstract class Column
{
    /** @var string[] */
    public array $relations = [];

    public string $table;

    public ?Closure $callback = null;

    private bool $sortable = false;

    private bool $searchable = false;

    public function __construct(
        public string $name,
        public ?string $field = null,
    ) {
        $this->field ??= (string) str($name)->snake();

        if (str_contains($this->field, '.')) {
            $this->relations = explode('.', str($field)->beforeLast('.'));
        }
    }

    public function asSqlField(string $table): string
    {
        if ($this->isBaseField()) {
            return "$table.$this->field";
        }

        return $this->field;
    }

    public function isBaseField(): bool
    {
        return ! str_contains($this->field, '.');
    }

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

    public function format(callable $callback): self
    {
        $this->callback = $callback;

        return $this;
    }

    public function getCallback(): ?Closure
    {
        return $this->callback;
    }

    public function getContent(Model $model): string
    {
        return is_callable($this->callback) ? call_user_func($this->callback, $model) : $this->render($model);
    }

    public function usableInQueries(): bool
    {
        return ! $this instanceof BladeColumn;
    }

    abstract public function render(Model $model): string;
}
