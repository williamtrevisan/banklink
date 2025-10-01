<?php

declare(strict_types=1);

namespace Banklink\Accessors\Contracts;

use Banklink\Entities\Transaction;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

interface TransactionsAccessor
{
    /**
     * @return Collection<int, Transaction>
     *
     * @throws BindingResolutionException
     */
    public function between(Carbon $start, Carbon $end): Collection;

    /**
     * @return Collection<int, Transaction>
     *
     * @throws BindingResolutionException
     */
    public function today(): Collection;
}
