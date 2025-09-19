<?php

declare(strict_types=1);

use Banklink\Banklink;
use Banklink\Facades;
use Brick\Math\BigNumber;
use Brick\Money\Money;

if (! function_exists('banklink')) {
    /**
     * Get a new banklink proxy object from the given token.
     *
     * @return ($token is null ? object : Banklink)
     */
    function banklink(?string $token = null)
    {
        if (func_num_args() === 0) {
            return new class
            {
                public function __call($method, $parameters)
                {
                    return Facades\Banklink::$method(...$parameters);
                }
            };
        }

        return Facades\Banklink::authenticate($token);
    }
}

if (! function_exists('money')) {
    /**
     * Get a new moneyable object from the given string.
     *
     * @param  string|null  $amount
     * @return ($amount is null ? object : Money)
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
                    return Money::of($this->normalize($amount), config('banklink.currency'));
                }

                private function normalize(BigNumber|float|int|string $amount): BigNumber|float|int|string
                {
                    if (! is_string($amount)) {
                        return $amount;
                    }

                    if (str($amount)->contains([','])) {
                        return str($amount)->replace(['.', ','], ['', '.'])->value();
                    }

                    return $amount;
                }
            };
        }

        return Money::of(
            str($amount)->replace(['.', ','], ['', '.'])->value(),
            config('banklink.currency'),
        );
    }
}
