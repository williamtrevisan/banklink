<?php

declare(strict_types=1);

namespace Banklink\Entities;

abstract class CardLimit
{
    abstract public function used(): string;

    abstract public function available(): string;

    abstract public function total(): string;
}
