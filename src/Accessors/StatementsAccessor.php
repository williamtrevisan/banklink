<?php

declare(strict_types=1);

namespace Banklink\Accessors;

use Banklink\Entities\CardStatement;

final readonly class StatementsAccessor
{
    public function __construct(
        private CardStatement $statement
    ) {}

    /**
     * @return CardStatement[]
     */
    public function all(): array
    {
        return $this->statement->all();
    }

    /**
     * @return CardStatement[]
     */
    public function byPeriod(string $period): array
    {
        return $this->statement->byPeriod($period);
    }
}
