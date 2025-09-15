<?php

declare(strict_types=1);

namespace Banklink\Entities;

use Banklink\Enums\StatementStatus;
use DateTimeImmutable;

abstract class CardStatement
{
    public function __construct(
        public readonly string $cardId,
        public readonly StatementStatus $status,
        public readonly DateTimeImmutable $dueDate,
        public readonly ?DateTimeImmutable $closingDate,
        public readonly string $amount,
        public readonly string $period,
        /* @var Holder[] */
        public readonly array $holders,
    ) {}

    /**
     * @return CardStatement[]
     */
    abstract public function all(): array;

    abstract public function byPeriod(string $period): array;

    final public function cardId(): string
    {
        return $this->cardId;
    }

    final public function status(): StatementStatus
    {
        return $this->status;
    }

    final public function dueDate(): DateTimeImmutable
    {
        return $this->dueDate;
    }

    final public function closingDate(): DateTimeImmutable
    {
        return $this->closingDate;
    }

    final public function amount(): string
    {
        return $this->amount;
    }

    final public function period(): string
    {
        return $this->period;
    }

    final public function holders(): array
    {
        return $this->holders;
    }

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
