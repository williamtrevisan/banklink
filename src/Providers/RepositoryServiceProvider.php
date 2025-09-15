<?php

declare(strict_types=1);

namespace Banklink\Providers;

use Banklink\Banks\Itau\Repositories\AuthenticationHttpRepository;
use Banklink\Banks\Itau\Repositories\CardHttpRepository;
use Banklink\Banks\Itau\Repositories\CheckingAccountHttpRepository;
use Banklink\Banks\Itau\Repositories\Contracts\AuthenticationRepository;
use Banklink\Banks\Itau\Repositories\Contracts\CardRepository;
use Banklink\Banks\Itau\Repositories\Contracts\CheckingAccountRepository;
use Banklink\Banks\Itau\Repositories\Contracts\MenuRepository;
use Banklink\Banks\Itau\Repositories\MenuHttpRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(AuthenticationRepository::class, fn (): AuthenticationHttpRepository => new AuthenticationHttpRepository(Http::itau()));
        $this->app->singleton(CardRepository::class, fn (): CardHttpRepository => new CardHttpRepository(Http::itau()));
        $this->app->singleton(CheckingAccountRepository::class, fn (): CheckingAccountHttpRepository => new CheckingAccountHttpRepository(Http::itau()));
        $this->app->singleton(MenuRepository::class, fn (): MenuHttpRepository => new MenuHttpRepository(Http::itau()));
    }
}
