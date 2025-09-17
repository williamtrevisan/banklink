<?php

declare(strict_types=1);

namespace Banklink\Entities;

use Banklink\Enums\StatementStatus;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

abstract class CardStatement
{
    /**
     * @return Collection<int, CardStatement>
     */
    abstract public function all(): Collection;

    /** @return Collection<int, CardStatement> */
    abstract public function byPeriod(string $period): Collection;

    abstract public function cardId(): string;

    abstract public function status(): StatementStatus;

    abstract public function dueDate(): Carbon;

    abstract public function closingDate(): ?Carbon;

    abstract public function amount(): string;

    abstract public function period(): string;

    /** @return Collection<int, Holder> */
    abstract public function holders(): Collection;
}
