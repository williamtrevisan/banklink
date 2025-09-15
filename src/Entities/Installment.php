<?php

declare(strict_types=1);

namespace Banklink\Entities;

use DateTimeImmutable;

abstract class Installment
{
    abstract public function current(): int;

    abstract public function total(): int;

    abstract public function dueDate(): DateTimeImmutable;

    abstract public function amount(): string;
}
