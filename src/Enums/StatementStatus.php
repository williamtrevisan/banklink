<?php

declare(strict_types=1);

namespace Banklink\Enums;

enum StatementStatus: string
{
    case Closed = 'closed';
    case Open = 'open';
    case Paid = 'paid';

    public static function fromString(string $status): self
    {
        return match ($status) {
            'fechada' => StatementStatus::Closed,
            'paga' => StatementStatus::Paid,
            default => StatementStatus::Open,
        };
    }

    public function isOpen(): bool
    {
        return $this === self::Open;
    }

    public function isClosed(): bool
    {
        return $this === self::Closed;
    }
}
