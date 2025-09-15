<?php

declare(strict_types=1);

namespace Banklink\Entities;

use DateTimeImmutable;

abstract class Installment
{
    public function __construct(
        public readonly int $current,
        public readonly int $total,
        public readonly DateTimeImmutable $dueDate,
        public readonly string $amount
    ) {}

    final public function current(): int
    {
        return $this->current;
    }

    final public function total(): int
    {
        return $this->total;
    }

    final public function dueDate(): DateTimeImmutable
    {
        return $this->dueDate;
    }

    final public function amount(): string
    {
        return $this->amount;
    }
}
