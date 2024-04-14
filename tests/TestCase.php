<?php

namespace VPremiss\Arabicable\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use VPremiss\Arabicable\ArabicableServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Factory::guessFactoryNamesUsing(
        //     fn (string $modelName) => 'VPremiss\\Arabicable\\Database\\Factories\\'.class_basename($modelName).'Factory'
        // );
    }

    protected function getPackageProviders($app)
    {
        return [
            ArabicableServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        // $migration = include __DIR__.'/../database/migrations/create_Arabicable_table.php.stub';
        // $migration->up();
    }
}
