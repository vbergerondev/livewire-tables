<?php

namespace Vbergeron\LivewireTables\Contracts;

use Illuminate\Database\Eloquent\Model;

interface Exportable
{
    public function canExport(): bool;
}
