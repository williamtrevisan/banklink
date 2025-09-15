<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Entities;

use Banklink\Entities;

final class Holder extends Entities\Holder
{
    public function __construct(
        private readonly string $name,
        private readonly string $lastFourDigits,
        private readonly string $amount,
        /** @var Transaction[] */
        private readonly array $transactions,
    ) {}

    public static function from(array $data): static
    {
        return new self(
            name: $data['nomeCliente'] ?? '',
            lastFourDigits: $data['numeroCartao'] ?? '',
            amount: $data['totalTitularidade'] ?? '',
            transactions: isset($data['lancamentos'])
                ? Transaction::fromCardTransaction($data['lancamentos'])
                : [],
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

    public function amount(): string
    {
        return $this->amount;
    }

    /** @return Transaction[] */
    public function transactions(): array
    {
        return $this->transactions;
    }
}
