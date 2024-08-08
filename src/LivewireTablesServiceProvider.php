<?php

namespace Vbergeron\LivewireTables;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Vbergeron\LivewireTables\Commands\LivewireTablesCommand;

class LivewireTablesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('livewire-tables')
            ->hasConfigFile()
            ->hasViews();
    }
}
