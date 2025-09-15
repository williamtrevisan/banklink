<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Repositories\Contracts;

use Illuminate\Support\Carbon;

interface CheckingAccountRepository
{
    public function navigation(): string;

    public function subNavigation(string $operation): string;

    public function statements(string $operation): string;

    public function transactionsFrom(Carbon $start, Carbon $end, string $operation): array;
}
