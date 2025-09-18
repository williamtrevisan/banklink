<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Entities;

use Banklink\Accessors\CardsAccessor;
use Banklink\Accessors\TransactionsAccessor;
use Banklink\Entities;

final class Account extends Entities\Account
{
    public function __construct(
        private readonly string $agency,
        private readonly string $number,
        private readonly string $digit,
        private readonly ?string $balance = null,
    ) {}

    public static function from(array $config): static
    {
        return new self(
            agency: $config['agency'],
            number: $config['account'],
            digit: $config['account_digit'],
        );
    }

    public function agency(): string
    {
        return $this->agency;
    }

    public function number(): string
    {
        return $this->number;
    }

    public function digit(): string
    {
        return $this->digit;
    }

    public function balance(): ?string
    {
        return $this->balance;
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
