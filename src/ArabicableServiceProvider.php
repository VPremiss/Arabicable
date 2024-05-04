<?php

declare(strict_types=1);

namespace VPremiss\Arabicable;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use VPremiss\Arabicable\Concerns\HasArabicableMigrationBlueprintMacros;
use VPremiss\Arabicable\Support\Concerns\HasConfigurationValidations;
use VPremiss\Crafty\Utilities\Configurated\Interfaces\Configurated;
use VPremiss\Crafty\Utilities\Configurated\Traits\ManagesConfigurations;
use VPremiss\Crafty\Utilities\Installable\Interfaces\Installable;
use VPremiss\Crafty\Utilities\Installable\Traits\HasInstallationCommand;

class ArabicableServiceProvider extends PackageServiceProvider implements Installable, Configurated
{
    use ManagesConfigurations;
    use HasConfigurationValidations;
    use HasInstallationCommand;
    use HasArabicableMigrationBlueprintMacros;

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
            ->hasMigration('create_common_arabic_texts_table');
    }

    public function packageRegistered()
    {
        $this->registerConfigurations();
    }

    public function bootingPackage()
    {
        $this->installationCommand();

        $this->arabicableMigrationBlueprintMacros();
    }

    public function seederFilePaths(): array
    {
        return [
            __DIR__ . '/../database/seeders/CommonArabicTextSeeder.php',
        ];
    }

    public function configurationValidations(): array
    {
        return [
            'arabicable' => [
                'property_suffix_keys' => [
                    'numbers_to_indian' => fn ($value) => $this->validatePropertySuffixKeysNumbersToIndianConfig($value),
                    'text_with_harakat' => fn ($value) => $this->validatePropertySuffixKeysTextWithHarakatConfig($value),
                    'text_for_search' => fn ($value) => $this->validatePropertySuffixKeysTextForSearchConfig($value),
                ],
                'spacing_after_punctuation_only' => fn ($value) => $this->validateSpacingAfterPunctuationOnlyConfig($value),
                'normalized_punctuation_marks' => fn ($value) => $this->validateNormalizedPunctuationMarksConfig($value),
                'space_preserved_enclosings' => fn ($value) => $this->validateSpacePreservedEnclosingsConfig($value),
                'common_arabic_text' => [
                    'model' => fn ($value) => $this->validateCommonArabicTextModelConfig($value),
                    'factory' => fn ($value) => $this->validateCommonArabicTextFactoryConfig($value),
                    'cache_key' => fn ($value) => $this->validateCommonArabicTextCacheKeyConfig($value),
                ],
            ],
        ];
    }
}
