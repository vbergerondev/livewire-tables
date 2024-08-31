<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;
use Vbergeron\LivewireTables\Columns\TextColumn;
use Vbergeron\LivewireTables\Filters\SelectFilter;
use Vbergeron\LivewireTables\LivewireTables;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertSame;

beforeEach(function () {
    $this->table = new class extends LivewireTables
    {
        protected function source(): string|array|Builder
        {
            return [
                [
                    'id' => 1,
                    'name' => 'John 1',
                    'email' => 'john+1@doe.com',
                    'admin' => false,
                ],
                [
                    'id' => 2,
                    'name' => 'John 2',
                    'email' => 'john+2@doe.com',
                    'admin' => false,
                ],
                [
                    'id' => 3,
                    'name' => 'John 3',
                    'email' => 'john+3@doe.com',
                    'admin' => false,
                ],
                [
                    'id' => 4,
                    'name' => 'John 4',
                    'email' => 'john+4@doe.com',
                    'admin' => false,
                ],
                [
                    'id' => 5,
                    'name' => 'John 5',
                    'email' => 'john+5@doe.com',
                    'admin' => false,
                ],
                [
                    'id' => 6,
                    'name' => 'John 6',
                    'email' => 'john+6@doe.com',
                    'admin' => false,
                ],
                [
                    'id' => 7,
                    'name' => 'John 7',
                    'email' => 'john+7@doe.com',
                    'admin' => false,
                ],
                [
                    'id' => 8,
                    'name' => 'John 8',
                    'email' => 'john+8@doe.com',
                    'admin' => false,
                ],
                [
                    'id' => 9,
                    'name' => 'John 9',
                    'email' => 'john+9@doe.com',
                    'admin' => false,
                ],
                [
                    'id' => 10,
                    'name' => 'John 10',
                    'email' => 'john+10@doe.com',
                    'admin' => false,
                ],
                [
                    'id' => 11,
                    'name' => 'John 11',
                    'email' => 'john+11@doe.com',
                    'admin' => false,
                ],
                [
                    'id' => 12,
                    'name' => 'John 12',
                    'email' => 'john+12@doe.com',
                    'admin' => false,
                ],
                [
                    'id' => 13,
                    'name' => 'John 13',
                    'email' => 'john+13@doe.com',
                    'admin' => false,
                ],
                [
                    'id' => 14,
                    'name' => 'John 14',
                    'email' => 'john+14@doe.com',
                    'admin' => false,
                ],
                [
                    'id' => 15,
                    'name' => 'John 15',
                    'email' => 'john+15@doe.com',
                    'admin' => true,
                ],
            ];
        }

        protected function columns(): array
        {
            return [
                (new TextColumn('email'))->searchable()->sortable(),
                (new TextColumn('name'))->sortable(),
                (new TextColumn('admin'))->sortable(),
            ];
        }

        protected function filters(): array
        {
            return [
                (new SelectFilter('admin', 'admin'))
                    ->withOptions([
                        true => true,
                        false => false,
                    ])->multiple(),
            ];
        }
    };
});

it('renders', function () {
    Livewire::test($this->table)
        ->assertOk();
});

it('creates a length aware paginator', function () {
    $rows = Livewire::test($this->table)->rows;

    assertSame(15, $rows->total());
    assertCount(10, $rows->all());
    assertInstanceOf(LengthAwarePaginator::class, $rows);
});

it('has pagination', function () {
    Livewire::test($this->table)
        ->assertSee('john+1@doe.com')
        ->assertDontSee('john+15@doe.com')
        ->call('setPage', 2)
        ->assertSee('john+15@doe.com')
        ->assertDontSee('john+1@doe.com');
});

it('has sorting', function () {
    Livewire::test($this->table)
        ->assertSeeInOrder(['john+1@doe.com', 'john+2@doe.com'])
        ->call('sortBy', 'email', true)
        ->assertSeeInOrder(['john+15@doe.com', 'john+14@doe.com'])
        ->call('sortBy', 'email')
        ->assertSeeInOrder(['john+1@doe.com', 'john+2@doe.com']);
});

it('has searching', function () {
    $component = Livewire::test($this->table)
        ->assertSee('john+2@doe.com');

    assertSame(15, $component->rows->total());

    $component->set('search', 'john+4@')
        ->assertDontSee('john+2@doe.com');

    assertSame(1, $component->rows->total());
});

it('returns empty results when no search results', function () {
    $component = Livewire::test($this->table)
        ->assertSee('john+2@doe.com');

    assertSame(15, $component->rows->total());

    $component->set('search', 'testtesttest');

    assertSame(0, $component->rows->total());
});

it('returns filtered items', function () {
    $component = Livewire::test($this->table)
        ->assertSeeInOrder(['john+1@doe.com', false])
        ->set('filters', ['admin' => true])
        ->assertSeeInOrder(['john+15@doe.com', true]);

    assertSame(1, $component->rows->total());

    // Now we will test when the filter is multiple
    $component = Livewire::test($this->table)
        ->set('filters', ['admin' => [true, false]]);

    assertSame(15, $component->rows->total());
});
