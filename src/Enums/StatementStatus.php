<?php

declare(strict_types=1);

namespace Banklink\Enums;

enum StatementStatus: string
{
    case Open = 'open';
    case Closed = 'closed';

    public function isOpen(): bool
    {
        return $this === self::Open;
    }

    public function isClosed(): bool
    {
        return $this === self::Closed;
    }
}
