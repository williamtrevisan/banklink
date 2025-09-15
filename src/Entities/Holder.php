<?php

declare(strict_types=1);

namespace Banklink\Entities;

abstract class Holder
{
    abstract public function name(): string;

    abstract public function lastFourDigits(): string;

    abstract public function amount(): string;

    /** @return Transaction[] */
    abstract public function transactions(): array;
}
