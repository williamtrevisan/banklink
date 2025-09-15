<?php

declare(strict_types=1);

namespace Banklink\Entities;

abstract class CardLimit
{
    abstract public function used(): string;

    abstract public function available(): string;

    abstract public function total(): string;

    final public function usagePercentage(): ?float
    {
        $totalValue = $this->parseAmount($this->total());
        if ($totalValue <= 0) {
            return null;
        }

        $usedValue = $this->parseAmount($this->used());

        return ($usedValue / $totalValue) * 100;
    }

    final public function isNearMax(): bool
    {
        $percentage = $this->usagePercentage();

        return $percentage !== null && $percentage > 80;
    }

    final public function isOverHalf(): bool
    {
        $percentage = $this->usagePercentage();

        return $percentage !== null && $percentage > 50;
    }

    final public function getRemaining(): string
    {
        return $this->available();
    }

    private function parseAmount(string $amount): float
    {
        return (float) str_replace(['.', ','], ['', '.'], $amount);
    }
}
