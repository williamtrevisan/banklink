<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Entities;

use Banklink\Accessors\CardsAccessor;
use Banklink\Accessors\TransactionsAccessor;
use Banklink\Entities;

final class Account extends Entities\Account
{
    public static function from(array $config): static
    {
        return new self(
            agency: $config['agency'],
            number: $config['account'],
            digit: $config['digit'],
        );
    }

    public function cards(): CardsAccessor
    {
        return app()->make(CardsAccessor::class);
    }

    public function transactions(): TransactionsAccessor
    {
        return app()->make(TransactionsAccessor::class);
    }
}
