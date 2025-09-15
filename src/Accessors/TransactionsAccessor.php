<?php

declare(strict_types=1);

namespace Banklink\Accessors;

use Banklink\Banks\Itau\Pipelines\CheckingAccountTransactionsGetter;
use Banklink\Entities\Transaction;
use Illuminate\Support\Carbon;

final class TransactionsAccessor
{
    /**
     * @return Transaction[]
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function between(Carbon $start, Carbon $end): array
    {
        return app()->make(CheckingAccountTransactionsGetter::class)
            ->from($start, $end);
    }

    /**
     * @return Transaction[]
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function today(): array
    {
        return $this->between(now()->startOfDay(), now()->endOfDay());
    }
}
