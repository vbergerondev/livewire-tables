<?php

namespace Vbergeron\LivewireTables\Columns\Traits;

use Closure;

trait Formatable
{
    public ?Closure $callback = null;

    public function format(Closure $callback): self
    {
        $this->callback = $callback;

        return $this;
    }

    public function getCallback(): ?Closure
    {
        return $this->callback;
    }
}
