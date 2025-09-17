<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Entities;

use Banklink\Entities;
use Banklink\Support\Date;
use Illuminate\Support\Carbon;

final class Installment extends Entities\Installment
{
    public function __construct(
        private readonly int $current,
        private readonly int $total,
        private readonly Carbon $dueDate,
        private readonly string $amount,
    ) {}

    public static function from(array $transaction): static
    {
        [$description] = str($transaction['descricao'])
            ->split('/\(?\d{1,2}\/\d{1,2}\)?$/', 2);

        [$current, $total] = str($transaction['descricao'])
            ->after($description)
            ->trim('()')
            ->explode('/');

        return new self(
            current: (int) $current,
            total: (int) $total,
            dueDate: Date::normalizePtBrDate($transaction['data'], now()->year),
            amount: $transaction['valor'] ?? ''
        );
    }

    public function current(): int
    {
        return $this->current;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function dueDate(): Carbon
    {
        return $this->dueDate;
    }

    public function amount(): string
    {
        return $this->amount;
    }
}
