<?php

namespace Vbergeron\LivewireTables\Columns;

use Closure;
use Illuminate\Database\Eloquent\Model;

final class BladeColumn extends Column
{
    public function __construct(string $name, private readonly string|Closure $view)
    {
        parent::__construct($name);
    }

    public function render(Model $model): string
    {
        if (is_callable($this->view)) {
            return call_user_func($this->view, $model)->render();
        }

        return view($this->view, ['model' => $model])->render();
    }
}
