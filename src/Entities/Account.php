<?php

declare(strict_types=1);

namespace Banklink\Entities;

use Banklink\Accessors\CardsAccessor;
use Banklink\Accessors\TransactionsAccessor;

abstract class Account
{
    abstract public function agency(): string;

    abstract public function number(): string;

    abstract public function digit(): string;

    abstract public function balance(): ?string;

    abstract public function cards(): CardsAccessor;

    abstract public function transactions(): TransactionsAccessor;
}
