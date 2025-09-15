<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Entities;

use Banklink\Entities;

final class Card extends Entities\Card
{
    public static function from(array $card): static
    {
        $statement = collect($card['faturas'])->firstWhere('status', 'aberta');

        return new self(
            id: $card['id'],
            name: $card['nome'],
            lastFourDigits: $card['numero'],
            brand: $card['bandeira'] ?? null,
            limit: CardLimit::from($card['limites']),
            statement: CardStatement::from($card['id'], $statement),
        );
    }
}
