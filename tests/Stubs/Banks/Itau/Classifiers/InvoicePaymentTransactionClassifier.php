<?php

declare(strict_types=1);

namespace Tests\Stubs\Banks\Itau\Classifiers;

use Banklink\Actions\Classifiers\Contracts\TransactionClassifier;
use Banklink\Enums\TransactionKind;

final class InvoicePaymentTransactionClassifier implements TransactionClassifier
{
    public function kind(): TransactionKind
    {
        return TransactionKind::InvoicePayment;
    }

    public function matches(string $description): bool
    {
        $patterns = [
            '/(INT\s+UNICLASS\s+VS)/i',
            '/(ITAU\s+MC|INT\s+ITAU\s+BLACK)/i',
        ];

        return collect($patterns)
            ->some(fn (string $pattern): bool => str($description)->isMatch($pattern));
    }
}
