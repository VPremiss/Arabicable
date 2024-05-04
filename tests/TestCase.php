<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;
use VPremiss\Arabicable\Concerns\HasArabicableMigrationBlueprintMacros;

use function Orchestra\Testbench\workbench_path;

#[WithMigration]
class TestCase extends Orchestra
{
    use WithWorkbench;
    use RefreshDatabase;
    use HasArabicableMigrationBlueprintMacros;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function ignorePackageDiscoveriesFrom()
    {
        return [
            'vpremiss/arabicable',
            'vpremiss/crafty',
        ];
    }

    protected function getPackageProviders($_)
    {
        return [
            \VPremiss\Crafty\CraftyServiceProvider::class,
            \VPremiss\Arabicable\ArabicableServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(workbench_path('database/migrations'));
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $this->arabicableMigrationBlueprintMacros();

        if (! env('IN_CI', false)) {
            $migration = include __DIR__ . '/../database/migrations/create_common_arabic_texts_table.php.stub';
            $migration->up();
        }
    }
}
