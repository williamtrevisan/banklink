<?php

declare(strict_types=1);

namespace Banklink\Support;

use DateTimeImmutable;

final class Date
{
    public static function normalizePtBrDate(string $date, int $year): DateTimeImmutable
    {
        $months = [
            'janeiro',
            'fevereiro',
            'março',
            'abril',
            'maio',
            'junho',
            'julho',
            'agosto',
            'setembro',
            'outubro',
            'novembro',
            'dezembro',
        ];

        if (preg_match('/(\d{1,2})\s*\/\s*([a-zç]+)/iu', $date, $matches)) {
            [$_, $day, $month] = $matches;
            $month = array_search($month, $months, true) ?? array_first($months);
            $month = ++$month;

            return DateTimeImmutable::createFromFormat('Y-m-d', "$year-$month-$day");
        }

        return DateTimeImmutable::createFromFormat('Y-m-d', $date);
    }
}
