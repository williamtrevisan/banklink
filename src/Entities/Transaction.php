<?php

declare(strict_types=1);

namespace Banklink\Entities;

use Banklink\Enums\TransactionDirection;
use Banklink\Enums\TransactionKind;
use Banklink\Enums\TransactionPaymentMethod;
use Banklink\Enums\TransactionType;
use Brick\Money\Money;
use Illuminate\Support\Carbon;

abstract class Transaction
{
    abstract public function date(): Carbon;

    abstract public function description(): string;

    abstract public function amount(): Money;

    abstract public function direction(): TransactionDirection;

    abstract public function kind(): TransactionKind;

    abstract public function paymentMethod(): TransactionPaymentMethod;

    abstract public function installments(): ?Installment;

    abstract public function isRefund(TransactionType $from): bool;

    final public function isCashback(): bool
    {
        return $this->matches(kind: TransactionKind::Cashback);
    }

    final public function isFee(): bool
    {
        return $this->matches(kind: TransactionKind::Fee);
    }

    final public function isInvoicePayment(): bool
    {
        return $this->matches(kind: TransactionKind::InvoicePayment);
    }

    private function matches(TransactionKind $kind): bool
    {
        $bank = config()->get('banklink.bank');

        return config()
            ->collection("banks.$bank.$kind->value.patterns", [])
            ->some(fn (string $pattern) => str($this->description())->isMatch($pattern));
    }
}
