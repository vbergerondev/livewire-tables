<?php

use Vbergeron\LivewireTables\Columns\BooleanColumn;
use Vbergeron\LivewireTables\Models\TableData;

describe('Boolean column', function () {

    it('renders boolean value', function () {
        $column = new BooleanColumn('is_admin', 'is_admin');
        expect(trim($column->render(new TableData(['is_admin' => '1']))))
            ->toBe(__('Yes'));

        $column = new BooleanColumn('is_admin', 'is_admin');
        expect(trim($column->render(new TableData(['is_admin' => '0']))))
            ->toBe(__('No'));
    });

})->covers(BooleanColumn::class);
