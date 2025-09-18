<?php

declare(strict_types=1);

namespace Banklink\Enums;

enum TransactionPaymentMethod: string
{
    case Credit = 'credit';
    case Debit = 'debit';

    public static function fromOperation(string $operation): self
    {
        return $operation === 'credito'
            ? TransactionPaymentMethod::Credit
            : TransactionPaymentMethod::Debit;
    }

    public function isCredit(): bool
    {
        return $this === TransactionPaymentMethod::Credit;
    }

    public function isDebit(): bool
    {
        return $this === TransactionPaymentMethod::Debit;
    }
}
