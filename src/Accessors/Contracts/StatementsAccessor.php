<?php

declare(strict_types=1);

namespace Banklink\Accessors\Contracts;

use Banklink\Entities\CardStatement;
use Illuminate\Support\Collection;

interface StatementsAccessor
{
    /**
     * @return Collection<int, CardStatement>
     */
    public function all(): Collection;
}
