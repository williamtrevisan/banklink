<?php

declare(strict_types=1);

use Brick\Math\BigNumber;
use Brick\Money\Money;

if (! function_exists('money')) {
    /**
     * Get a new moneyable object from the given string.
     *
     * @param  string|null  $string
     * @return ($string is null ? object : Money)
     */
    function money(BigNumber|int|float|string|null $amount = null)
    {
        if (func_num_args() === 0) {
            return new class
            {
                public function __call($method, $parameters)
                {
                    return Money::$method(...$parameters);
                }

                public function of(BigNumber|float|int|string $amount): Money
                {
                    return Money::of(
                        str($amount)->replace(['.', ','], ['', '.'])->value(),
                        config('banklink.currency')
                    );
                }
            };
        }

        return Money::of(
            str($amount)->replace(['.', ','], ['', '.'])->value(),
            config('banklink.currency'),
        );
    }
}
