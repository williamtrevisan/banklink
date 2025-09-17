<?php

declare(strict_types=1);

namespace Banklink\Entities;

use Illuminate\Support\Collection;

abstract class Holder
{
    abstract public function name(): string;

    abstract public function lastFourDigits(): string;

    abstract public function amount(): string;

    /** @return Collection<int, Transaction> */
    abstract public function transactions(): Collection;
}
