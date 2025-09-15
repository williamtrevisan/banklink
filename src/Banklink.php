<?php

declare(strict_types=1);

namespace Banklink;

use Banklink\Contracts\Bank;

final readonly class Banklink
{
    public function __construct(private Bank $bank) {}

    public function authenticate(): Bank
    {
        return $this->bank->authenticate();
    }
}
