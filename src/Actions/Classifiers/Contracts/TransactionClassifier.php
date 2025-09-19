<?php

declare(strict_types=1);

namespace Banklink\Actions\Classifiers\Contracts;

use Banklink\Enums\TransactionKind;

interface TransactionClassifier
{
    public function kind(): TransactionKind;

    public function matches(string $description): bool;
}
