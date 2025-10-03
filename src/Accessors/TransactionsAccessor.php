<?php

declare(strict_types=1);

namespace Banklink\Accessors;

use Banklink\Banks\Itau\Pipelines\CheckingAccountTransactionsGetter;
use Banklink\Entities\Transaction;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class TransactionsAccessor implements Contracts\TransactionsAccessor
{
    /**
     * @return Collection<int, Transaction>
     *
     * @throws BindingResolutionException
     */
    public function between(Carbon $start, Carbon $end): Collection
    {
        $key = $this->cacheKey('between', [
            $start->toDateTimeString(),
            $end->toDateTimeString(),
        ]);

        return Cache::remember(
            $key,
            ttl: now()->addMinutes(30),
            callback: fn () => app()->make(CheckingAccountTransactionsGetter::class)->from($start, $end),
        );
    }

    /**
     * @return Collection<int, Transaction>
     *
     * @throws BindingResolutionException
     */
    public function today(): Collection
    {
        $key = $this->cacheKey('today', [
            now()->toDateString(),
        ]);

        return Cache::remember(
            $key,
            ttl: now()->addMinutes(10),
            callback: fn () => $this->between(now()->startOfDay(), now()->endOfDay()),
        );
    }

    private function cacheKey(string $method, array $params = []): string
    {
        $bank = config('banklink.bank');
        $agency = config("banks.$bank.agency");
        $account = config("banks.$bank.account");

        $baseKey = "banklink.$bank.$agency.$account.transactions.$method";

        return empty($params)
            ? $baseKey
            : $baseKey.'.'.md5(serialize($params));
    }
}
