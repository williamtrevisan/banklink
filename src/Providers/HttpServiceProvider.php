<?php

declare(strict_types=1);

namespace Banklink\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

final class HttpServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Http::macro('itau', fn () => Http::baseUrl(config('banklink.banks.itau.base_url'))
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0',
            ]));
    }
}
