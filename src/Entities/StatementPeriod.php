<?php

declare(strict_types=1);

namespace Banklink\Entities;

use Illuminate\Support\Carbon;
use InvalidArgumentException;
use Stringable;

final readonly class StatementPeriod implements Stringable
{
    private function __construct(
        private string $period,
    ) {}

    public function __toString(): string
    {
        return $this->period;
    }

    public static function fromDate(Carbon $date): self
    {
        return new self(period: $date->format('Y-m'));
    }

    public static function fromString(string $period): self
    {
        if (! str($period)->isMatch('/^\d{4}-\d{2}$/')) {
            throw new InvalidArgumentException("Invalid period format [$period]. Expected YYYY-MM.");
        }

        return new self(period: $period);
    }

    public function value(): string
    {
        return $this->period;
    }

    public function year(): string
    {
        [$year] = str($this->period())->explode('-');

        return $year;
    }

    public function month(): string
    {
        [, $month] = str($this->period())->explode('-');

        return $month;
    }
}
