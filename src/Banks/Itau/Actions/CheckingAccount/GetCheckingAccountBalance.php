<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Actions\CheckingAccount;

use Banklink\Banks\Itau\Repositories\Contracts\CheckingAccountRepository;
use Brick\Money\Money;

final readonly class GetCheckingAccountBalance
{
    public function __construct(
        private CheckingAccountRepository $checkingAccountRepository,
    ) {}

    public function get(string $operation): Money
    {
        $balance = $this->checkingAccountRepository
            ->balance($operation);

        $amount = money()->of($balance->get('valor'));

        return $balance->get('positivo')
            ? $amount
            : $amount->negated();
    }
}
