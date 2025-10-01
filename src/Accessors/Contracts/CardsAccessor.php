<?php

namespace Banklink\Accessors\Contracts;

use Banklink\Entities\Card;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;

interface CardsAccessor
{
    /**
     * @return Collection<int, Card>
     *
     * @throws BindingResolutionException
     */
    public function all(): Collection;

    /**
     * @throws BindingResolutionException
     */
    public function firstWhere(string $key, mixed $value): ?Card;
}
