<?php

declare(strict_types=1);

use Banklink\Banklink;
use Brick\Math\BigNumber;
use Brick\Money\Money;

if (! function_exists('banklink')) {
    /**
     * Get a new banklink proxy object from the given token.
     *
<<<<<<< HEAD
     * @return ($string is null ? object : Banklink)
=======
     * @param string|null $token
     * @return ($token is null ? object : Banklink)
>>>>>>> 55c9ffa (feat(itau): implement itau support)
     */
    function banklink(?string $token = null)
    {
        if (func_num_args() === 0) {
            return new class
            {
                public function __call($method, $parameters)
                {
                    return Banklink::$method(...$parameters);
                }
            };
        }

        return Banklink::authenticate($token);
    }
}

if (! function_exists('money')) {
    /**
     * Get a new moneyable object from the given string.
     *
<<<<<<< HEAD
     * @param  string|null  $string
     * @return ($string is null ? object : Money)
=======
     * @param string|null $amount
     * @return ($amount is null ? object : Money)
>>>>>>> 55c9ffa (feat(itau): implement itau support)
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
