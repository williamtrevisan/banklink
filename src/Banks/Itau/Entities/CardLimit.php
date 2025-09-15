<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Entities;

final class CardLimit extends \Banklink\Entities\CardLimit
{
    public static function from(array $limits): static
    {
        return new self(
            used: (float) $limits['limiteCreditoUtilizadoValor'],
            available: (float) $limits['limiteCreditoDisponivelValor'],
            total: (float) $limits['limiteCreditoValor']
        );
    }
}
