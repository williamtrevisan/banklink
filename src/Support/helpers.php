<?php

declare(strict_types=1);

use Banklink\Support\SessionStore;

if (! function_exists('session')) {
    function session(): SessionStore
    {
        return new SessionStore();
    }
}
