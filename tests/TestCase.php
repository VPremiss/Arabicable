<?php

namespace VPremiss\Arabicable\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use VPremiss\Arabicable\ArabicableServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
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
    }
}
