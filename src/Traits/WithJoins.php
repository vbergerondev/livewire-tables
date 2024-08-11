<?php

namespace Vbergeron\LivewireTables\Traits;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Vbergeron\LivewireTables\Columns\Column;

trait WithJoins
{
    private function applyJoins(Builder $builder, Closure $next): Builder
    {
        collect($this->tableColumns)
            ->reject(fn (Column $column) => $column->isBaseField())
            ->map(function (Column $column) use ($builder) {
                $alias = $relationship = (string) str($column->field)->beforeLast('.');

                $model = $builder->getModel();

                /** @var Relation $relation */
                $relation = $model->$relationship();

                $relatedTable = $relation->getRelated()->getTable();

                if ($relation instanceof BelongsTo) {
                    $localKey = $relation->getOwnerKeyName();
                    $foreignKey = $relation->getForeignKeyName();
                    $builder->leftJoin("$relatedTable as $alias", "{$model->getTable()}.$foreignKey", '=', "$alias.$localKey");
                }

                if ($relation instanceof HasOne) {
                    $localKey = $relation->getLocalKeyName();
                    $foreignKey = $relation->getForeignKeyName();
                    $builder->leftJoin("$relatedTable as $alias", "{$model->getTable()}.$localKey", '=', "$alias.$foreignKey");
                }
            });

        return $next($builder);
    }
}
