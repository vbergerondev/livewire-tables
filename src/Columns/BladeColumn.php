<?php

namespace Vbergeron\LivewireTables\Columns;

use Closure;
use Illuminate\Database\Eloquent\Model;

final class BladeColumn extends Column
{
    private string|Closure $view;

    public function view(string|Closure $view): self
    {
        $this->view = $view;

        return $this;
    }

    public function render(Model $model): string
    {
        if (is_callable($this->view)) {
            return call_user_func($this->view, $model)->render();
        }

        return view($this->view, ['model' => $model])->render();
    }
}
