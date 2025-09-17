<?php

declare(strict_types=1);

namespace Banklink\Entities;

use Illuminate\Support\Carbon;

abstract class Transaction
{
    abstract public function date(): Carbon;

    abstract public function description(): string;

    abstract public function amount(): string;

    abstract public function sign(): string;

    abstract public function paymentMethod(): string;

    abstract public function installments(): ?Installment;
}
