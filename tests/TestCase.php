<?php

namespace Vbergeron\LivewireTables\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Vbergeron\LivewireTables\LivewireTablesServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Vbergeron\\LivewireTables\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            LivewireTablesServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('app.key', 'base64:bXoycjJhd2w1bDVxaTVvZWVuNDVwMGkzdndkZnZ3czE=');
        config()->set('view.paths', [__DIR__.'/../tests/views']);

        /*
        $migration = include __DIR__.'/../database/migrations/create_livewire-tables_table.php.stub';
        $migration->up();
        */
    }
}
