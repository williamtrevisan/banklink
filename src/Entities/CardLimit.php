<?php

declare(strict_types=1);

namespace Banklink\Entities;

use Brick\Money\Money;

abstract class CardLimit
{
    abstract public function used(): Money;

    abstract public function available(): Money;

    abstract public function total(): Money;
}
