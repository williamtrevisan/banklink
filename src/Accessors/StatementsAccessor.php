<?php

declare(strict_types=1);

namespace Banklink\Accessors;

use Banklink\Entities\CardStatement;
use Banklink\Entities\StatementPeriod;
use Illuminate\Support\Collection;

final readonly class StatementsAccessor implements Contracts\StatementsAccessor
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

    public function byPeriod(StatementPeriod $period): Collection
    {
        return $this->statement->byPeriod($period);
    }
}
