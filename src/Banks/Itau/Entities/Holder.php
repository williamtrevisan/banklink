<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Entities;

use Banklink\Entities;

final class Holder extends Entities\Holder
{
    public static function from(array $data): static
    {
        return new self(
            name: $data['nomeCliente'] ?? '',
            lastFourDigits: $data['numeroCartao'] ?? '',
            amount: $data['totalTitularidade'] ?? '',
            transactions: isset($data['lancamentos'])
                ? Transaction::fromCardTransaction($data['lancamentos'])
                : collect(),
        );
    }
}
