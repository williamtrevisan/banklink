<?php

declare(strict_types=1);

namespace Banklink\Entities;

use Banklink\Accessors\CardsAccessor;
use Banklink\Accessors\TransactionsAccessor;

abstract class Account
{
    public function __construct(
        public readonly string $agency,
        public readonly string $number,
        public readonly string $digit,
        public readonly ?string $balance = null,
    ) {}

    abstract public function cards(): CardsAccessor;

    abstract public function transactions(): TransactionsAccessor;
}
