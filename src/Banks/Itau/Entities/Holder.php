<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Entities;

use Banklink\Entities;
use Brick\Money\Money;
use Illuminate\Support\Collection;

final class Holder extends Entities\Holder
{
    public function __construct(
        private readonly string $name,
        private readonly string $lastFourDigits,
        private readonly Money $amount,
        /** @var Collection<int, Transaction> */
        private readonly Collection $transactions,
    ) {}

    public static function from(array $data): static
    {
        $transactions = collect($data['lancamentos'] ?? [])
            ->tap(function (Collection $transactions): void {
                if ($transactions->isEmpty()) {
                    return;
                }

                session()->put('card_transactions', $transactions);
            })
            ->map(fn (array $transaction): Transaction => Transaction::fromCardTransaction($transaction));

        return new self(
            name: $data['nomeCliente'],
            lastFourDigits: $data['numeroCartao'],
            amount: money()->of($data['totalTitularidade'] ?? $transactions->sum('amount')),
            transactions: $transactions,
        );
    }

    public function name(): string
    {
        return $this->name;
    }

    public function lastFourDigits(): string
    {
        return $this->lastFourDigits;
    }

    public function amount(): Money
    {
        return $this->amount;
    }

    /** @return Collection<int, Transaction> */
    public function transactions(): Collection
    {
        return $this->transactions;
    }
}
