<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Repositories\Contracts;

interface CardRepository
{
    public function details(): string;

    public function all(): array;

    public function statementBy(string $cardId): array;
}
