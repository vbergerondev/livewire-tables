<?php

namespace Vbergeron\LivewireTables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Pipeline;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Vbergeron\LivewireTables\Columns\Column;
use Vbergeron\LivewireTables\Filters\Filter;
use Vbergeron\LivewireTables\Models\TableData;
use Vbergeron\LivewireTables\Traits\WithFiltering;
use Vbergeron\LivewireTables\Traits\WithJoins;
use Vbergeron\LivewireTables\Traits\WithPageSize;
use Vbergeron\LivewireTables\Traits\WithSearching;
use Vbergeron\LivewireTables\Traits\WithSelectableColumns;
use Vbergeron\LivewireTables\Traits\WithSorting;

/**
 * @property-read Filter[] $tableFilters
 * @property-read Column[] $tableColumns
 * @property-read LengthAwarePaginator<Model> $rows
 * @property-read Builder<Model>|null $queryBuilder
 * @property-read string $tableName
 */
abstract class LivewireTables extends Component
{
    use WithFiltering;
    use WithJoins;
    use WithPageSize;
    use WithPagination;
    use WithSearching;
    use WithSelectableColumns;
    use WithSorting;

    public function render(): View
    {
        return view('livewire-tables::index');
    }

    #[Computed]
    public function tableName(): string
    {
        return str(class_basename($this))->kebab();
    }

    /**
     * @return LengthAwarePaginator<Model>
     */
    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        if ($this->queryBuilder instanceof Builder) {
            return $this->paginatedQueryBuilder();
        }

        return $this->paginatedArray();
    }

    /**
     * @return Filter[]
     */
    #[Computed]
    public function tableFilters(): array
    {
        return $this->filters();
    }

    /**
     * @return Column[]
     */
    #[Computed]
    public function tableColumns(): array
    {
        return $this->columns();
    }

    /**
     * @return Builder<Model>|null
     */
    #[Computed]
    public function queryBuilder(): ?Builder
    {
        $source = $this->source();
        if ($source instanceof Builder) {
            return $source;
        }

        if (is_string($source) && is_subclass_of($source, Model::class)) {
            return $source::query();
        }

        return null;
    }

    /**
     * @return LengthAwarePaginator<Model>
     */
    private function paginatedQueryBuilder(): LengthAwarePaginator
    {
        /** @var Builder<Model> $builder */
        $builder = Pipeline::send($this->queryBuilder)
            ->through([
                $this->applyJoins(...),
                $this->applySort(...),
                $this->applySearch(...),
                $this->applyFilters(...),
            ])
            ->thenReturn();

        $table = $builder->getModel()->getTable();

        $builder->addSelect(
            collect($this->tableColumns)
                ->reject(fn (Column $column) => ! $column->isQueryable())
                ->map(fn (Column $column): string => ($column->isBaseField() ? "$table.$column->field" : $column->field)." AS $column->field")
                ->all()
        );

        return $builder->paginate($this->pageSize);
    }

    /**
     * @return LengthAwarePaginator<Model>
     */
    private function paginatedArray(): LengthAwarePaginator
    {
        $items = array_map(fn (array $item): Model => (new TableData)->setRawAttributes(Arr::dot($item)), $this->source());

        $items = Pipeline::send($items)
            ->through([
                $this->applySearchArray(...),
                $this->applyFiltersArray(...),
                $this->applySortArray(...),
            ])
            ->thenReturn();

        $offset = max(0, ($this->getPage() - 1) * $this->pageSize);

        return new LengthAwarePaginator(
            array_slice($items, (int) $offset, $this->pageSize),
            count($items),
            $this->pageSize,
            $this->getPage(),
            [],
        );
    }

    public function void(): void
    {
        // Does nothing and will be called by various components to let
        // Livewire sync its frontend and backend state
    }

    /**
     * @return class-string<Model>|array|Builder
     */
    abstract protected function source(): string|array|Builder;

    /** @return Column[] */
    abstract protected function columns(): array;

    abstract protected function filters(): array;
}
