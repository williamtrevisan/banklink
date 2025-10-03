<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Repositories\Contracts;

use Banklink\Entities\Card;
use Illuminate\Support\Collection;

interface CardRepository
{
    public function details(): string;

    public function all(): Collection;

    public function statementBy(Card $card): Collection;
}
