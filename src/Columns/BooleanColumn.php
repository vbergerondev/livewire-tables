<?php

namespace Vbergeron\LivewireTables\Columns;

use Illuminate\Database\Eloquent\Model;

final class BooleanColumn extends Column
{
    public function render(Model $model): string
    {
        return filter_var($model->{$this->field}, FILTER_VALIDATE_BOOLEAN)
            ? __('Yes')
            : __('No');
    }
}
