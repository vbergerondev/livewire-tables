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
use Vbergeron\LivewireTables\Traits\WithFiltering;
use Vbergeron\LivewireTables\Traits\WithJoins;
use Vbergeron\LivewireTables\Traits\WithPageSize;
use Vbergeron\LivewireTables\Traits\WithSearching;
use Vbergeron\LivewireTables\Traits\WithSorting;

/**
 * @property Filter[] $tableFilters
 * @property Column[] $tableColumns
 * @property LengthAwarePaginator<Model> $rows
 * @property Builder<Model>|null $queryBuilder
 * @property Column[] $selectedColumns
 */
abstract class LivewireTables extends Component
{
    use WithFiltering;
    use WithJoins;
    use WithPageSize;
    use WithPagination;
    use WithSearching;
    use WithSorting;

    public function setSelectedColumns(array $columns)
    {
        cache()->put('fields', $columns, now()->addWeek());
        unset($this->selectedColumns);
    }

    /**
     * @return Column[]
     */
    #[Computed]
    public function selectedColumns(): array
    {
        // Coming from cache or db...
        $fields = cache()->get('fields', []);

        if ($fields === []) {
            return $this->tableColumns;
        }

        return array_filter(
            array_map(
                fn ($field): ?Column => collect($this->tableColumns)->firstWhere(fn (Column $column): bool => $column->field === $field),
                $fields,
            )
        );
    }

    public function render(): View
    {
        return view('livewire-tables::index');
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
    #[Computed(persist: true)]
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

    private function paginatedQueryBuilder(): LengthAwarePaginator
    {
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
            array_map(fn (Column $column) => ($column->isBaseField() && $column->usableInQueries()
                    ? "$table.$column->field"
                    : $column->field)." AS $column->field",
                $this->tableColumns)
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

    /**
     * @return class-string<Model>|array|Builder
     */
    abstract protected function source(): string|array|Builder;

    /** @return Column[] */
    abstract protected function columns(): array;

    abstract protected function filters(): array;
}
