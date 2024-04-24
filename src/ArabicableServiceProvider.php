<?php

declare(strict_types=1);

namespace VPremiss\Arabicable;

use Illuminate\Support\Facades\File;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use VPremiss\Arabicable\Concerns\HasArabicableMigrationBlueprintMacros;
use VPremiss\Arabicable\Support\Concerns\HasValidatedConfiguration;
use VPremiss\Crafty\Utilities\Configurated\Interfaces\Configurated;

class ArabicableServiceProvider extends PackageServiceProvider implements Configurated
{
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
            ->hasMigration('create_common_arabic_texts_table')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command->publishConfigFile();

                $command->publishMigrations();
                $command->askToRunMigrations();

                // ? Seeding common-Arabic-text into database
                if (!app()->environment('testing')) {
                    // Publish the seeder file
                    $this->publishes([
                        __DIR__ . '/../database/seeders/CommonArabicTextSeeder.php' => ($seederPath = database_path('seeders/CommonArabicTextSeeder.php')),
                    ], 'arabicable-seeders');
                    $command->publish('seeders');

                    if (File::exists($seederPath)) {
                        // Correct the seeder's namespace
                        File::put(
                            $seederPath,
                            str_replace(
                                'namespace VPremiss\Arabicable\Database\Seeders;',
                                'namespace Database\Seeders;',
                                File::get($seederPath),
                            ),
                        );
                        // Seed into the database
                        $command->startWith(
                            fn (InstallCommand $command) => $command->call('db:seed', [
                                '--class' => 'CommonArabicTextSeeder',
                                '--force',
                            ])
                        );
                    }

                    // Prepend into the DatabaseSeeder for continuous migration upon `fresh`ing
                    if (File::exists($databaseSeederPath = database_path("seeders/DatabaseSeeder.php"))) {
                        $fileContents = File::get($databaseSeederPath);

                        if (strpos($fileContents, "\$this->call(CommonArabicTextSeeder::class);") === false) {
                            $searchPattern = strpos($fileContents, "public function run(): void\n    {") !== false ?
                                "public function run(): void\n    {" :
                                "public function run()\n    {";
                            $replacePattern = $searchPattern === "public function run(): void\n    {" ?
                                "public function run(): void\n    {\n        \$this->call(CommonArabicTextSeeder::class);" :
                                "public function run()\n    {\n        \$this->call(CommonArabicTextSeeder::class);";
                            $newContents = str_replace(
                                $searchPattern,
                                $replacePattern,
                                $fileContents
                            );

                            File::put($databaseSeederPath, $newContents);
                        }
                    }
                }

                $command->askToStarRepoOnGitHub('vpremiss/arabicable');
            });
    }

    public function bootingPackage()
    {
        $this->arabicableMigrationBlueprintMacros();
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
