<?php

declare(strict_types=1);

namespace Banklink\Accessors;

use Banklink\Entities\CardStatement;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final readonly class StatementsAccessor implements Contracts\StatementsAccessor
{
    public function __construct(
        private CardStatement $statement
    ) {}

    /**
     * @return Collection<int, CardStatement>
     */
    public function all(): Collection
    {
        $bank = config()->get('banklink.bank');
        $agency = config()->get("banks.$bank.agency");
        $account = config()->get("banks.$bank.account");

        return Cache::remember(
            key: "banklink.$bank.$agency.$account.statements.all",
            ttl: now()->addMonth(),
            callback: fn () => $this->statement->all(),
        );
    }
}
