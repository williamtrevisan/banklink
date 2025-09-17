<?php

declare(strict_types=1);

namespace Banklink\Support;

use Illuminate\Support\Carbon;

final class Date
{
    public static function normalizePtBrDate(string $date, int $year): Carbon
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
            $month = collect($months)->search($month) ?: collect($months)->first();
            $month = ++$month;

            return Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
        }

        return Carbon::createFromFormat('Y-m-d', $date);
    }
}
