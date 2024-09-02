<?php

use Vbergeron\LivewireTables\Columns\TextColumn;
use Vbergeron\LivewireTables\Models\TableData;

describe('Text column', function () {

    it('renders model field value', function () {
        // Using a callback
        $column = new TextColumn('firstname');
        expect(trim($column->render(new TableData(['firstname' => 'Vincent']))))
            ->toBe('Vincent');
    });

})->covers(TextColumn::class);
