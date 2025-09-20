<?php

declare(strict_types=1);

namespace Banklink\Entities;

use Banklink\Enums\StatementStatus;
use Brick\Money\Money;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

abstract class CardStatement
{
    /**
     * @return Collection<int, CardStatement>
     */
    abstract public function all(): Collection;

    abstract public function cardId(): string;

    abstract public function status(): StatementStatus;

    abstract public function dueDate(): Carbon;

    abstract public function closingDate(): ?Carbon;

    abstract public function amount(): Money;

    abstract public function period(): StatementPeriod;

    /** @return Collection<int, Holder> */
    abstract public function holders(): Collection;
}
