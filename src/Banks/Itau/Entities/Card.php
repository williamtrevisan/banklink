<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Entities;

use Banklink\Accessors;
use Banklink\Entities;
use Banklink\Enums\CardBrand;
use Illuminate\Support\Carbon;

final class Card extends Entities\Card
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $lastFourDigits,
        private readonly CardBrand $brand,
        private readonly CardLimit $limit,
        private readonly ?CardStatement $statement,
        private readonly int $dueDay,
    ) {}

    public static function from(array $card): static
    {
        $statement = collect($card['faturas'])
            ->firstWhere(fn (array $statement) => str($statement['descricao'])->contains('aberta'));

        $card = new self(
            id: $card['id'],
            name: $card['nome'],
            lastFourDigits: $card['numero'],
            brand: CardBrand::from(str($card['bandeira'])->lower()->value()),
            limit: CardLimit::from($card['limites']),
            statement: null,
            dueDay: Carbon::parse($card['vencimento'])->day,
        );

        return $card->withStatement(CardStatement::from($card, $statement));
    }

    public function withStatement(CardStatement $statement): static
    {
        return new static(
            id: $this->id,
            name: $this->name,
            lastFourDigits: $this->lastFourDigits,
            brand: $this->brand,
            limit: $this->limit,
            statement: $statement,
            dueDay: $this->dueDay,
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

    public function brand(): CardBrand
    {
        return $this->brand;
    }

    public function limit(): CardLimit
    {
        return $this->limit;
    }

    public function statements(): Accessors\Contracts\StatementsAccessor
    {
        return new Accessors\StatementsAccessor($this->statement);
    }

    public function dueDay(): int
    {
        return $this->dueDay;
    }
}
