<?php

declare(strict_types=1);

namespace VPremiss\Arabicable\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;
use VPremiss\Arabicable\Concerns\HasArabicableMigrationBlueprintMacros;

class TestCase extends Orchestra
{
    use WithWorkbench;
    use RefreshDatabase;
    use HasArabicableMigrationBlueprintMacros;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function getEnvironmentSetUp($app)
    {
        $this->enforceConfigurations();

        $this->arabicableMigrationBlueprintMacros();
    }

    protected function enforceConfigurations()
    {
        $localTimezone = 'Asia/Riyadh';
        config()->set('app.timezone', !env('IN_CI') ? $localTimezone : 'UTC');

        config()->set('app.locale', 'ar');
        config()->set('app.faker_locale', 'ar_SA');

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');

        config()->set('cache.default', 'memcached');
    }
}
