<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Repositories\Contracts;

use Illuminate\Support\Collection;

interface CardRepository
{
    public function details(): string;

    public function all(): Collection;

    public function statementBy(string $cardId): Collection;
}
