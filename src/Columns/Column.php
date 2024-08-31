<?php

namespace Vbergeron\LivewireTables\Columns;

use Illuminate\Database\Eloquent\Model;
use Vbergeron\LivewireTables\Columns\Traits\Formatable;
use Vbergeron\LivewireTables\Columns\Traits\Searchable;
use Vbergeron\LivewireTables\Columns\Traits\Sortable;

abstract class Column
{
    use Formatable;
    use Searchable;
    use Sortable;

    /** @var string[] */
    public array $relations = [];

    public function __construct(
        public string $name,
        public ?string $field = null,
    ) {
        $this->field ??= (string) str($name)->snake();

        if (str_contains($this->field, '.')) {
            $this->relations = explode('.', str($field)->beforeLast('.'));
        }
    }

    public function isBaseField(): bool
    {
        return $this->field !== null && ! str_contains($this->field, '.');
    }

    public function getContent(Model $model): string
    {
        return is_callable($this->callback) ? call_user_func($this->callback, $model) : $this->render($model);
    }

    public function isQueryable(): bool
    {
        return ! $this instanceof BladeColumn;
    }

    abstract public function render(Model $model): string;
}
