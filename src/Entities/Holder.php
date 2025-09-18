<?php

declare(strict_types=1);

namespace Banklink\Entities;

use Brick\Money\Money;
use Illuminate\Support\Collection;

abstract class Holder
{
    abstract public function name(): string;

    abstract public function lastFourDigits(): string;

    abstract public function amount(): Money;

    /** @return Collection<int, Transaction> */
    abstract public function transactions(): Collection;
}
