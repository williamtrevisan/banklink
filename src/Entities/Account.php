<?php

declare(strict_types=1);

namespace Banklink\Entities;

use Banklink\Accessors\Contracts\CardsAccessor;
use Banklink\Accessors\Contracts\TransactionsAccessor;
use Brick\Money\Money;

abstract class Account
{
    abstract public function bank(): string;

    abstract public function agency(): string;

    abstract public function number(): string;

    abstract public function digit(): string;

    abstract public function balance(): Money;

    abstract public function cards(): CardsAccessor;

    abstract public function transactions(): TransactionsAccessor;
}
