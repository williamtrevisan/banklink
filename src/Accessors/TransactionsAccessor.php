<?php

declare(strict_types=1);

namespace Banklink\Accessors;

use Banklink\Banks\Itau\Pipelines\CheckingAccountTransactionsGetter;
use Banklink\Entities\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final class TransactionsAccessor
{
    /**
     * @return Collection<int, Transaction>
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function between(Carbon $start, Carbon $end): Collection
    {
        return app()->make(CheckingAccountTransactionsGetter::class)
            ->from($start, $end);
    }

    /**
     * @return Collection<int, Transaction>
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function today(): Collection
    {
        return $this->between(now()->startOfDay(), now()->endOfDay());
    }
}
