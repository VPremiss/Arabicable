<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use VPremiss\Arabicable\ArabicableServiceProvider;
use VPremiss\Arabicable\Concerns\HasArabicBlueprintMacros;
use VPremiss\Arabicable\Database\Seeders\CommonArabicTextSeeder;

use function Orchestra\Testbench\artisan;

class TestCase extends Orchestra
{
    use HasArabicBlueprintMacros;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'VPremiss\\Arabicable\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );

        artisan(
            $this,
            'db:seed',
            [
                '--force',
                '--class' => CommonArabicTextSeeder::class,
            ],
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ArabicableServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $this->arabicBlueprintMacros();

        config()->set('database.default', 'testing');

        $migration = include __DIR__ . '/../database/migrations/create_common_arabic_texts_table.php.stub';
        $migration->up();
    }
}
