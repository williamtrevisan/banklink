<?php

declare(strict_types=1);

namespace Banklink\Accessors;

use Banklink\Entities\CardStatement;
use Illuminate\Support\Collection;

final readonly class StatementsAccessor
{
    public function __construct(
        private CardStatement $statement
    ) {}

    /**
     * @return Collection<int, CardStatement>
     */
    public function all(): Collection
    {
        return $this->statement->all();
    }

    /**
     * @return Collection<int, CardStatement>
     */
    public function byPeriod(string $period): Collection
    {
        return $this->statement->byPeriod($period);
    }
}
