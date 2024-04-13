<?php

namespace VPremiss\Arabicable;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use VPremiss\Arabicable\Concerns\HasArabicBlueprintMacros;

class ArabicableServiceProvider extends PackageServiceProvider
{
    use HasArabicBlueprintMacros;

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
}
