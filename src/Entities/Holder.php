<?php

declare(strict_types=1);

namespace Banklink\Entities;

abstract class Holder
{
    public function __construct(
        public readonly string $name,
        public readonly string $lastFourDigits,
        public readonly string $amount,
        /* @var Transaction[] */
        public readonly array $transactions
    ) {}

    final public function name(): string
    {
        return $this->name;
    }

    final public function lastFourDigits(): string
    {
        return $this->lastFourDigits;
    }

    final public function amount(): string
    {
        return $this->amount;
    }

    final public function transactions(): array
    {
        return $this->transactions;
    }
}
