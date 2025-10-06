<?php

declare(strict_types=1);

namespace Banklink;

use Banklink\Contracts\Bank;
use Illuminate\Support\Facades\Cache;

final readonly class Banklink
{
    public function __construct(private Bank $bank) {}

    public function authenticate(string $token): Bank
    {
        $bank = config()->get('banklink.bank');
        $agency = config()->get("banks.$bank.agency");
        $account = config()->get("banks.$bank.account");

        return Cache::remember(
            key: "banklink.$bank.$agency.$account",
            ttl: now()->addMonth(),
            callback: fn () => $this->bank->authenticate($token),
        );
    }
}
