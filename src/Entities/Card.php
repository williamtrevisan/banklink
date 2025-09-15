<?php

declare(strict_types=1);

namespace Banklink\Entities;

use Banklink\Accessors\StatementsAccessor;

abstract class Card
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $lastFourDigits,
        public readonly string $brand,
        public readonly CardLimit $limit,
        public readonly CardStatement $statement,
    ) {}

    final public function id(): string
    {
        return $this->id;
    }

    final public function name(): string
    {
        return $this->name;
    }

    final public function lastFourDigits(): string
    {
        return $this->lastFourDigits;
    }

    final public function brand(): string
    {
        return $this->brand;
    }

    final public function limit(): CardLimit
    {
        return $this->limit;
    }

    final public function statements(): StatementsAccessor
    {
        return new StatementsAccessor($this->statement);
    }
}
