<?php

namespace Vbergeron\LivewireTables;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Vbergeron\LivewireTables\Contracts\CachedSelectedColumns;
use Vbergeron\LivewireTables\Contracts\SelectedColumnsStorageInterface;

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
            ->hasViews()
            ->hasTranslations();
    }

    public function register()
    {
        parent::register();

        $this->app->singleton(SelectedColumnsStorageInterface::class, CachedSelectedColumns::class);
    }
}
