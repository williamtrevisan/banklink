<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Repositories;

use Banklink\Banks\Itau\Entities\Transaction;
use Banklink\Banks\Itau\Repositories\Contracts\CheckingAccountRepository;
use Carbon\Carbon;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;

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

    public function transactionsFrom(Carbon $start, Carbon $end, string $operation): array
    {
        $transactions = $this->http
            ->replaceHeaders([
                'op' => $operation,
            ])
            ->asForm()
            ->post('/router-app/router', [
                'dataInicio' => $start,
                'dataFinal' => $end,
            ])
            ->json('lancamentos');

        return array_map(
            fn (array $transaction): Transaction => Transaction::fromCheckingAccountTransaction($transaction),
            array_filter($transactions, fn (array $transaction): bool => ! is_null($transaction['dataLancamento']))
        );
    }
}
