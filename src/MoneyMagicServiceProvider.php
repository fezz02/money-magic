<?php

namespace Fezz\MoneyMagic;

use Fezz\MoneyMagic\Commands\MoneyMagicCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MoneyMagicServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('money-magic')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_money_magic_table')
            ->hasCommand(MoneyMagicCommand::class);
    }
}
