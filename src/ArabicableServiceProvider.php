<?php

declare(strict_types=1);

namespace VPremiss\Arabicable;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use VPremiss\Arabicable\Concerns\HasArabicableMigrationBlueprintMacros;
use VPremiss\Arabicable\Support\Concerns\HasValidatedConfiguration;
use VPremiss\Crafty\Utilities\Configurated\Interfaces\Configurated;
use VPremiss\Crafty\Utilities\Installable\Interfaces\Installable;
use VPremiss\Crafty\Utilities\Installable\Traits\HasInstallationCommand;

class ArabicableServiceProvider extends PackageServiceProvider implements Installable, Configurated
{
    use HasInstallationCommand;
    use HasValidatedConfiguration;
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

    public function configValidation(string $configKey): void
    {
        match ($configKey) {
            'arabicable.property_suffix_keys' => $this->validatePropertySuffixKeysConfig(),
            'arabicable.spacing_after_punctuation_only' => $this->validateSpacingAfterPunctuationOnlyConfig(),
            'arabicable.normalized_punctuation_marks' => $this->validateNormalizedPunctuationMarksConfig(),
            'arabicable.space_preserved_enclosings' => $this->validateSpacePreservedEnclosingsConfig(),
            'arabicable.common_arabic_text' => $this->validateCommonArabicTextConfig(),
        };
    }

    public function configDefault(string $configKey): mixed
    {
        return match ($configKey) {
            'arabicable.property_suffix_keys.numbers_to_indian' => '_indian',
            'arabicable.property_suffix_keys.text_with_harakat' => '_with_harakat',
            'arabicable.property_suffix_keys.text_for_search' => '_searchable',
            'arabicable.spacing_after_punctuation_only' => false,
            'arabicable.normalized_punctuation_marks' => [
                '«' => ['<', '<<'],
                '»' => ['>', '>>'],
            ],
            'arabicable.space_preserved_enclosings' => [
                '{', '}',
            ],
            'arabicable.common_arabic_text.model' => \VPremiss\Arabicable\Models\CommonArabicText::class,
            'arabicable.common_arabic_text.factory' => \VPremiss\Arabicable\Database\Factories\CommonArabicTextFactory::class,
        };
    }
}
