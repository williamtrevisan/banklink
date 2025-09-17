<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Pipelines;

use Banklink\Banks\Itau\Actions\Account\GetCheckingAccountNavigation;
use Banklink\Banks\Itau\Actions\Account\GetCheckingAccountStatement;
use Banklink\Banks\Itau\Actions\Account\GetCheckingAccountSubNavigation;
use Banklink\Banks\Itau\Actions\Account\GetCheckingAccountTransactions;
use Banklink\Entities\Transaction;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final class CheckingAccountTransactionsGetter
{
    /**
     * @return Collection<int, Transaction>
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function from(Carbon $start, Carbon $end): Collection
    {
        $transactionOperation = app(Pipeline::class)
            ->through([
                GetCheckingAccountNavigation::class,
                GetCheckingAccountSubNavigation::class,
                GetCheckingAccountStatement::class,
            ])
            ->via('get')
            ->thenReturn();

        return app()->make(GetCheckingAccountTransactions::class)
            ->from($start, $end, $transactionOperation);
    }
}
