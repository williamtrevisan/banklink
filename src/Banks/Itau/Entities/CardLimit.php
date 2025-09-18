<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Entities;

use Brick\Money\Money;

final class CardLimit extends \Banklink\Entities\CardLimit
{
    public function __construct(
        private readonly Money $used,
        private readonly Money $available,
        private readonly Money $total,
    ) {}

    public static function from(array $limits): static
    {
        return new self(
            used: money()->of($limits['limiteCreditoUtilizadoValor'] ?? 0),
            available: money()->of($limits['limiteCreditoDisponivelValor'] ?? 0),
            total: money()->of($limits['limiteCreditoValor'] ?? 0),
        );
    }

    public function used(): Money
    {
        return $this->used;
    }

    public function available(): Money
    {
        return $this->available;
    }

    public function total(): Money
    {
        return $this->total;
    }
}
