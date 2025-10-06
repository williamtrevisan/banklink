<?php

declare(strict_types=1);

namespace Banklink;

use Banklink\Contracts\Bank;
use Illuminate\Support\Facades\Cache;

final readonly class Banklink
{
    public function __construct(private Bank $bank) {}

    public function authenticate(?string $token = null): Bank
    {
        $bank = config()->get('banklink.bank');
        $agency = config()->get("banks.$bank.agency");
        $account = config()->get("banks.$bank.account");

        if (! cache()->has($key = "banklink.$bank.$agency.$account"))
        {
            throw new \InvalidArgumentException('The token parameter is required.');
        }

        return Cache::remember(
            $key,
            ttl: now()->addMonth(),
            callback: fn () => $this->bank->authenticate($token),
        );
    }
}
