<?php

namespace Vbergeron\LivewireTables\Columns;

final class TextColumn extends Column
{
    public function render(object $model): string
    {
        return (string) data_get($model, $this->field);
    }
}
