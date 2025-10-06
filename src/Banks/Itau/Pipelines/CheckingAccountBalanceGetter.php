<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Pipelines;

use Banklink\Banks\Itau\Actions\CheckingAccount\GetCheckingAccountBalance;
use Banklink\Banks\Itau\Actions\CheckingAccount\GetCheckingAccountNavigation;
use Banklink\Banks\Itau\Actions\CheckingAccount\GetCheckingAccountStatement;
use Banklink\Banks\Itau\Actions\CheckingAccount\GetCheckingAccountSubNavigation;
use Brick\Money\Money;
use Illuminate\Pipeline\Pipeline;

final class CheckingAccountBalanceGetter
{
    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function get(): Money
    {
        $transactionOperation = app(Pipeline::class)
            ->through([
                GetCheckingAccountNavigation::class,
                GetCheckingAccountSubNavigation::class,
                GetCheckingAccountStatement::class,
            ])
            ->via('get')
            ->thenReturn();

        return app()->make(GetCheckingAccountBalance::class)
            ->get($transactionOperation);
    }
}
