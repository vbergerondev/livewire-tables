<?php

use Illuminate\Database\Eloquent\Model;
use Vbergeron\LivewireTables\Columns\Column;
use Vbergeron\LivewireTables\Columns\Traits\Formatable;
use Vbergeron\LivewireTables\TableData;

describe('Abstract column', function () {

    it('checks if column is a base field', function () {
        expect(
            (new DummyColumn('test', 'test'))->isBaseField()
        )->toBeTrue();

        expect(
            (new DummyColumn('test', 'test.test'))->isBaseField()
        )->toBeFalse();
    });

    it('checks if column is queryable', function () {
        expect(
            (new DummyColumn('test', 'test'))->isQueryable()
        )->toBeTrue();
    });

    it('returns content', function () {
        expect(
            (new DummyColumn('test', 'test'))->getContent(new TableData)
        )->toBe('test');
    });

})->covers(Column::class);

describe('Formatable trait', function () {
    it('returns actual callback', function () {
        $column = (new DummyColumn('test', 'test'))->format(fn (): string => 'Hello from callback!');
        expect(
            $column->getContent(new TableData)
        )->toBe('Hello from callback!');
    });

    it('returns content from callback', function () {
        $column = (new DummyColumn('test', 'test'))->format(fn (): string => 'Hello from callback!');
        expect($column->getCallback())->toBeInstanceOf(Closure::class);
    });
})->covers(Formatable::class);

class DummyColumn extends Column
{
    public function render(Model $model): string
    {
        return 'test';
    }
}
