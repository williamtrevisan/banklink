<?php

declare(strict_types=1);

namespace Banklink\Entities;

abstract class CardLimit
{
    public function __construct(
        public readonly float $used,
        public readonly float $available,
        public readonly float $total,
    ) {}

    final public function used(): float
    {
        return $this->used;
    }

    final public function available(): float
    {
        return $this->available;
    }

    final public function total(): float
    {
        return $this->total;
    }

    final public function getUsagePercentage(): ?float
    {
        if (! $this->total || $this->total <= 0) {
            return null;
        }

        $used = $this->used ?? 0;

        return ($used / $this->total) * 100;
    }

    final public function isNearMax(): bool
    {
        $percentage = $this->getUsagePercentage();

        return $percentage !== null && $percentage > 80;
    }

    final public function isOverHalf(): bool
    {
        $percentage = $this->getUsagePercentage();

        return $percentage !== null && $percentage > 50;
    }

    final public function getRemaining(): ?float
    {
        if ($this->available !== null) {
            return $this->available;
        }

        if ($this->total !== null && $this->used !== null) {
            return $this->total - $this->used;
        }

        return null;
    }

    final public function isComplete(): bool
    {
        return $this->used !== null && $this->available !== null && $this->total !== null;
    }
}
