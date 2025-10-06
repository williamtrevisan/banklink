<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Entities;

use Banklink\Accessors;
use Banklink\Banks\Itau\Pipelines\CheckingAccountBalanceGetter;
use Banklink\Entities;
use Brick\Money\Money;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Cache;

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

    public function balance(): Money
    {
        $bank = config()->get('banklink.bank');
        $agency = config()->get("banks.$bank.agency");
        $account = config()->get("banks.$bank.account");

        return Cache::remember(
            "banklink.$bank.$agency.$account.balance",
            ttl: now()->addHour(),
            callback: fn () => app()->make(CheckingAccountBalanceGetter::class)->get(),
        );
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
