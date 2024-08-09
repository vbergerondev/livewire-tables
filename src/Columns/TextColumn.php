<?php

namespace Vbergeron\LivewireTables\Columns;

use Illuminate\Database\Eloquent\Model;

final class TextColumn extends Column
{
    public function render(Model $model): string
    {
        return (string) $model->{$this->field};
    }
}
