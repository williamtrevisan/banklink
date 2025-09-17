<?php

declare(strict_types=1);

namespace Banklink;

use Banklink\Providers\AppServiceProvider;
use Banklink\Providers\HttpServiceProvider;
use Banklink\Providers\RepositoryServiceProvider;
use Illuminate\Support\ServiceProvider;

final class BanklinkServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/banklink.php' => config_path('banklink.php'),
        ], 'banklink-config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/banklink.php', 'banklink');

        $this->app->register(HttpServiceProvider::class);
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(AppServiceProvider::class);
    }
}
