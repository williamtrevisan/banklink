<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Entities;

use Banklink\Accessors;
use Banklink\Entities;
use Illuminate\Config\Repository;

final class Account extends Entities\Account
{
    public function __construct(
        private readonly string $bank,
        private readonly string $agency,
        private readonly string $number,
        private readonly string $digit,
    ) {}

    public static function from(Repository $config): static
    {
        return new self(
            bank: $bank = $config->get('banklink.bank'),
            agency: $config->get("banklink.banks.$bank.agency"),
            number: $config->get("banklink.banks.$bank.account"),
            digit: $config->get("banklink.banks.$bank.account_digit"),
        );
    }

    public function bank(): string
    {
        return $this->bank;
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

    public function cards(): Accessors\Contracts\CardsAccessor
    {
        return app()->make(Accessors\CardsAccessor::class);
    }

    public function transactions(): Accessors\Contracts\TransactionsAccessor
    {
        return app()->make(Accessors\TransactionsAccessor::class);
    }
}
