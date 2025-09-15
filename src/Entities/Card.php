<?php

declare(strict_types=1);

namespace Banklink\Entities;

use Banklink\Accessors\StatementsAccessor;

abstract class Card
{
    abstract public function id(): string;

    abstract public function name(): string;

    abstract public function lastFourDigits(): string;

    abstract public function brand(): string;

    abstract public function limit(): CardLimit;

    abstract public function statements(): StatementsAccessor;
}
