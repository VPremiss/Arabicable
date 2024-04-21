<?php

declare(strict_types=1);

namespace VPremiss\Arabicable;

use Illuminate\Support\Facades\File;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use VPremiss\Arabicable\Concerns\HasArabicBlueprintMacros;
use VPremiss\Arabicable\Concerns\HasInitialValidations;
use VPremiss\Arabicable\Database\Seeders\CommonArabicTextSeeder;

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

                if (!app()->environment('testing')) {
                    $this->publishes([
                        __DIR__.'/../database/seeders/CommonArabicTextSeeder.php' => ($seederPath = database_path('seeders/CommonArabicTextSeeder.php')),
                    ], 'arabicable-seeders');
                    $command->publish('seeders');
                    File::put(
                        $seederPath,
                        str_replace(
                            'namespace VPremiss\Arabicable\Database\Seeders;',
                            'namespace Database\Seeders;',
                            File::get($seederPath),
                        ),
                    );
                    $command->startWith(
                        fn (InstallCommand $command) => $command->call('db:seed', [
                            '--class' => 'CommonArabicTextSeeder',
                            '--force',
                        ])
                    );
                }

                $command->askToStarRepoOnGitHub('vpremiss/arabicable');
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
