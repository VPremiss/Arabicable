<?php

declare(strict_types=1);

namespace VPremiss\Arabicable;

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
            ->hasConfigFile();
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
