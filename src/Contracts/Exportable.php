<?php

namespace Vbergeron\LivewireTables\Contracts;

interface Exportable
{
    public function canExport(): bool;
}
