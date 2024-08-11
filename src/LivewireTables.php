<?php

namespace Vbergeron\LivewireTables;

use Exception;
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
use Vbergeron\LivewireTables\Traits\WithFiltering;
use Vbergeron\LivewireTables\Traits\WithJoins;
use Vbergeron\LivewireTables\Traits\WithPageSize;
use Vbergeron\LivewireTables\Traits\WithSearching;
use Vbergeron\LivewireTables\Traits\WithSorting;

abstract class LivewireTables extends Component
{
    use WithFiltering;
    use WithJoins;
    use WithPageSize;
    use WithPagination;
    use WithSearching;
    use WithSorting;

    private ?Builder $queryBuilder = null;

    /**
     * @throws Exception
     */
    public function boot(): void
    {
        $source = $this->source();
        if ($source instanceof Builder) {
            $this->queryBuilder = $source;
        }

        if (is_string($source)) {
            $this->queryBuilder = $source::query();
        }
    }

    public function render(): View
    {
        return view('livewire-tables::index');
    }

    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        if ($this->queryBuilder instanceof Builder) {
            return $this->paginatedQueryBuilder();
        }

        return $this->paginatedArray();
    }

    #[Computed]
    public function tableFilters(): array
    {
        return $this->filters();
    }

    #[Computed]
    public function tableColumns(): array
    {
        return $this->columns();
    }

    private function paginatedQueryBuilder(): LengthAwarePaginator
    {
        $this->queryBuilder = Pipeline::send($this->queryBuilder)
            ->through([
                $this->applyJoins(...),
                $this->applySort(...),
                $this->applySearch(...),
                $this->applyFilters(...),
            ])
            ->thenReturn();

        $table = $this->queryBuilder->getModel()->getTable();
        $this->queryBuilder->addSelect(
            array_map(fn (Column $column) => ($column->isBaseField() ? "$table.$column->field" : $column->field)." AS $column->field", $this->tableColumns)
        );

        return $this->queryBuilder->paginate($this->pageSize);
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
                $this->applySortArray(...),
                $this->applyFiltersArray(...),
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
