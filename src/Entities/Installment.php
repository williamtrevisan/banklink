<?php

declare(strict_types=1);

namespace Banklink\Entities;

use Illuminate\Support\Carbon;

abstract class Installment
{
    abstract public function current(): int;

    abstract public function total(): int;

    abstract public function dueDate(): Carbon;

    abstract public function amount(): string;
}
