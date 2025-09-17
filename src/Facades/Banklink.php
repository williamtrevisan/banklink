<?php

declare(strict_types=1);

namespace Banklink\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Banklink\Contracts\Bank authenticate(string $token)
 */
final class Banklink extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'banklink';
    }
}
