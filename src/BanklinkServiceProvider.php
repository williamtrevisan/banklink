<?php

declare(strict_types=1);

namespace Banklink;

use Banklink\Providers\AppServiceProvider;
use Banklink\Providers\HttpServiceProvider;
use Banklink\Providers\RepositoryServiceProvider;
use Illuminate\Support\ServiceProvider;

final class BanklinkServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/banklink.php', 'banklink');

        $this->app->register(HttpServiceProvider::class);
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(AppServiceProvider::class);
    }
}
