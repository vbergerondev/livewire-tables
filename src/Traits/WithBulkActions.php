<?php

namespace Vbergeron\LivewireTables\Traits;

use Exception;
use ReflectionException;
use ReflectionMethod;

trait WithBulkActions
{
    /** @var string[] */
    public array $selectedRows = [];

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function callBulkAction(string $action): void
    {
        if (! array_key_exists($action, $this->tableBulkActions)) {
            throw new Exception(
                sprintf('Bulk action method [%s] does not exist', $action)
            );
        }

        if ((new ReflectionMethod($this, $action))->isPrivate()) {
            throw new Exception(
                sprintf('Bulk action method [%s] must be either public or protected.', $action)
            );
        }

        call_user_func($this->$action(...), $this->selectedRows);
    }
}
