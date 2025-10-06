<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Actions\CheckingAccount;

use Banklink\Banks\Itau\Entities\Transaction;
use Banklink\Banks\Itau\Repositories\Contracts\CheckingAccountRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final readonly class GetCheckingAccountTransactions
{
    public function __construct(
        private CheckingAccountRepository $checkingAccountRepository,
    ) {}

    public function from(Carbon $start, Carbon $end, string $operation): Collection
    {
        return $this->checkingAccountRepository
            ->transactionsFrom($start, $end, $operation)
            ->reject(fn (Transaction $transaction): bool => str($transaction->description())->contains(['SALDO']));
    }
}
