<?php

declare(strict_types=1);

namespace Banklink\Enums;

enum TransactionDirection: string
{
    case Inflow = 'inflow';
    case Outflow = 'outflow';

    public static function fromSign(bool $isPositive): self
    {
        return $isPositive
            ? TransactionDirection::Inflow
            : TransactionDirection::Outflow;
    }

    public function isInflow(): bool
    {
        return $this === TransactionDirection::Inflow;
    }

    public function isOutflow(): bool
    {
        return $this === TransactionDirection::Outflow;
    }
}
