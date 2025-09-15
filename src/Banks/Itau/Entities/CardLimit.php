<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Entities;

final class CardLimit extends \Banklink\Entities\CardLimit
{
    public function __construct(
        private readonly string $used,
        private readonly string $available,
        private readonly string $total,
    ) {}

    public static function from(array $limits): static
    {
        return new self(
            used: $limits['limiteCreditoUtilizadoValor'] ?? '0,00',
            available: $limits['limiteCreditoDisponivelValor'] ?? '0,00',
            total: $limits['limiteCreditoValor'] ?? '0,00'
        );
    }

    public function used(): string
    {
        return $this->used;
    }

    public function available(): string
    {
        return $this->available;
    }

    public function total(): string
    {
        return $this->total;
    }
}
