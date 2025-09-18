<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Repositories;

use Banklink\Banks\Itau\Entities\Transaction;
use Banklink\Banks\Itau\Repositories\Contracts\CheckingAccountRepository;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final readonly class CheckingAccountHttpRepository implements CheckingAccountRepository
{
    public function __construct(public Factory|PendingRequest $http) {}

    public function navigation(): string
    {
        return $this->http
            ->withHeaders([
                'op' => session()->get('checking_account_operation'),
                'X-Auth-Token' => session()->get('auth_token'),
                'X-Flow-ID' => session()->get('flow_id'),
                'X-Client-ID' => session()->get('client_id'),
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->post('/router-app/router')
            ->body();
    }

    public function subNavigation(string $operation): string
    {
        return $this->http
            ->replaceHeaders([
                'op' => $operation,
            ])
            ->post('/router-app/router')
            ->body();
    }

    public function statements(string $operation): string
    {
        return $this->http
            ->replaceHeaders([
                'op' => $operation,
            ])
            ->post('/router-app/router')
            ->body();
    }

    public function transactionsFrom(Carbon $start, Carbon $end, string $operation): Collection
    {
        return $this->http
            ->replaceHeaders([
                'op' => $operation,
            ])
            ->asForm()
            ->post('/router-app/router', [
                'dataInicio' => $start->format('d-m-Y'),
                'dataFinal' => $end->format('d-m-Y'),
            ])
            ->collect('lancamentos')
            ->tap(fn (Collection $transactions) => session()->put('checking_account_transactions', $transactions))
            ->reject(fn (array $transaction): bool => is_null($transaction['dataLancamento']))
            ->map(fn (array $transaction): Transaction => Transaction::fromCheckingAccountTransaction($transaction));
    }
}
