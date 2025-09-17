<?php

declare(strict_types=1);

namespace Tests;

use Banklink\Providers\BanklinkServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }

    protected function getPackageProviders($app): array
    {
        return [
            BanklinkServiceProvider::class,
        ];
    }
}
