<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Entities;

use Banklink\Accessors\StatementsAccessor;
use Banklink\Entities;

final class Card extends Entities\Card
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $lastFourDigits,
        private readonly string $brand,
        private readonly CardLimit $limit,
        private readonly CardStatement $statement,
    ) {}

    public static function from(array $card): static
    {
        $statement = collect($card['faturas'])->firstWhere('status', 'aberta');

        return new self(
            id: $card['id'],
            name: $card['nome'],
            lastFourDigits: $card['numero'],
            brand: $card['bandeira'] ?? '',
            limit: CardLimit::from($card['limites']),
            statement: CardStatement::from($card['id'], $statement),
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function lastFourDigits(): string
    {
        return $this->lastFourDigits;
    }

    public function brand(): string
    {
        return $this->brand;
    }

    public function limit(): CardLimit
    {
        return $this->limit;
    }

    public function statements(): StatementsAccessor
    {
        return new StatementsAccessor($this->statement);
    }
}
