<?php

use Vbergeron\LivewireTables\Columns\BladeColumn;
use Vbergeron\LivewireTables\TableData;

describe('Blade column', function () {

    it('renders html', function () {
        // Using a callback
        $column = new BladeColumn('test', fn () => view('test'));
        expect(trim($column->render(new TableData)))
            ->toBe('<h1>Test</h1>');

        // Using a string
        $column = new BladeColumn('test', 'test');
        expect(trim($column->render(new TableData)))
            ->toBe('<h1>Test</h1>');
    });

})->covers(BladeColumn::class);
