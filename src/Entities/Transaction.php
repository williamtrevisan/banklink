<?php

declare(strict_types=1);

namespace Banklink\Entities;

use Banklink\Actions\Classifiers\Contracts\TransactionClassifier;
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

    abstract public function statementPeriod(): ?StatementPeriod;

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
            ->collection("banklink.banks.$bank.classifiers", [])
            ->some(function (string $classifierClass) use ($kind): bool {
                /** @var TransactionClassifier $classifier */
                $classifier = app()->make($classifierClass);

                return $classifier->kind() === $kind
                    && $classifier->matches($this->description());
            });
    }
}
