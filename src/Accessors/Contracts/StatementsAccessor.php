<?php

namespace Banklink\Accessors\Contracts;

use Banklink\Entities\CardStatement;
use Banklink\Entities\StatementPeriod;
use Illuminate\Support\Collection;

interface StatementsAccessor
{
    /**
     * @return Collection<int, CardStatement>
     */
    public function all(): Collection;

    public function byPeriod(StatementPeriod $period): Collection;
}
