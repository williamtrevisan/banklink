<?php

declare(strict_types=1);

namespace Banklink\Entities;

use Banklink\Accessors\StatementsAccessor;
use Banklink\Enums\CardBrand;

abstract class Card
{
    abstract public function id(): string;

    abstract public function name(): string;

    abstract public function lastFourDigits(): string;

    abstract public function brand(): CardBrand;

    abstract public function limit(): CardLimit;

    abstract public function statements(): StatementsAccessor;

    abstract public function dueDay(): int;

    final public function closingDay(): int
    {
        $bank = config('banklink.bank');

        return now()->addMonth()
            ->setDay($this->dueDay())
            ->subDays(config()->integer("banklink.banks.$bank.days_before_due_day"))
            ->day;
    }
}
