<?php

declare(strict_types=1);

namespace Banklink\Entities;

use DateTimeImmutable;

abstract class Transaction
{
    public function __construct(
        public readonly DateTimeImmutable $date,
        public readonly string $description,
        public readonly string $amount,
        public readonly string $sign,
        public readonly string $paymentMethod = 'credit',
        public readonly ?Installment $installments = null,
    ) {}

    final public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    final public function description(): string
    {
        return $this->description;
    }

    final public function amount(): string
    {
        return $this->amount;
    }

    final public function sign(): string
    {
        return $this->sign;
    }

    final public function paymentMethod(): string
    {
        return $this->paymentMethod;
    }

    final public function installments(): ?Installment
    {
        return $this->installments;
    }
}
