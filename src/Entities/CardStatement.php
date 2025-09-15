<?php

declare(strict_types=1);

namespace Banklink\Entities;

use Banklink\Enums\StatementStatus;
use DateTimeImmutable;

abstract class CardStatement
{
    /**
     * @return CardStatement[]
     */
    abstract public function all(): array;

    /** @return CardStatement[] */
    abstract public function byPeriod(string $period): array;

    abstract public function cardId(): string;

    abstract public function status(): StatementStatus;

    abstract public function dueDate(): DateTimeImmutable;

    abstract public function closingDate(): ?DateTimeImmutable;

    abstract public function amount(): string;

    abstract public function period(): string;

    /** @return Holder[] */
    abstract public function holders(): array;

    final public function isOpen(): bool
    {
        return $this->status()->isOpen();
    }

    final public function isClosed(): bool
    {
        return $this->status()->isClosed();
    }

    final public function daysUntilDue(): ?int
    {
        if (! $this->dueDate()) {
            return null;
        }

        $now = new DateTimeImmutable();

        return $now->diff($this->dueDate())->days;
    }

    final public function isOverdue(): bool
    {
        if (! $this->dueDate()) {
            return false;
        }

        $now = new DateTimeImmutable();

        return $now > $this->dueDate();
    }
}
