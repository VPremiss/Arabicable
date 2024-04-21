<?php

declare(strict_types=1);

namespace VPremiss\Arabicable;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use VPremiss\Arabicable\Concerns\HasArabicBlueprintMacros;
use VPremiss\Arabicable\Concerns\HasInitialValidations;

class ArabicableServiceProvider extends PackageServiceProvider
{
    use HasArabicBlueprintMacros;
    use HasInitialValidations;

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('arabicable')
            ->hasConfigFile()
            ->hasMigration('create_common_arabic_texts_table')
            ->hasInstallCommand(function(InstallCommand $command) {
                $command->publishConfigFile();

                $command->publishMigrations();
                $command->askToRunMigrations();

                $this->publishes([
                    __DIR__.'/../database/seeders/CommonArabicTextSeeder.php' => database_path('seeders/CommonArabicTextSeeder.php'),
                ], 'arabicable-seeders');
                $command->publish('seeders');
                $command->startWith(
                    fn (InstallCommand $command) => $command->call('db:seed', ['--force', '--class' => 'CommonArabicTextSeeder'])
                );

                $command->askToStarRepoOnGitHub('VPremiss/Arabicable');
            });
    }

    public function bootingPackage()
    {
        $this->arabicBlueprintMacros();
    }

    public function packageBooted()
    {
        $this->validations();
    }
}
