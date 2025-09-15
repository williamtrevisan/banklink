<?php

declare(strict_types=1);

namespace Banklink\Contracts;

use Banklink\Entities\Account;

interface Bank
{
    public function authenticate(): static;

    public function account(): Account;
}
