<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Repositories\Contracts;

interface MenuRepository
{
    public function get(string $operation): string;

    public function load(string $operation): void;
}
