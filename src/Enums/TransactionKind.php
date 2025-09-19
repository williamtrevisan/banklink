<?php

declare(strict_types=1);

namespace Banklink\Enums;

use Banklink\Entities\Transaction;

enum TransactionKind: string
{
    case Cashback = 'cashback';
    case Fee = 'fee';
    case InvoicePayment = 'invoice_payment';
    case Purchase = 'purchase';
    case Refund = 'refund';

    public static function fromTransaction(Transaction $transaction, TransactionType $transactionType): self
    {
        return match (true) {
            $transaction->isCashback() => TransactionKind::Cashback,
            $transaction->isFee() => TransactionKind::Fee,
            $transaction->isRefund(from: $transactionType) => TransactionKind::Refund,
            $transaction->isInvoicePayment() => TransactionKind::InvoicePayment,
            default => TransactionKind::Purchase,
        };
    }
}
