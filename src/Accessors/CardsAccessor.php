<?php

declare(strict_types=1);

namespace Banklink\Accessors;

use Banklink\Banks\Itau\Pipelines\CardsGetter;
use Banklink\Entities\Card;

final class CardsAccessor
{
    /**
     * @return Card[]
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function all(): array
    {
        return app()->make(CardsGetter::class)
            ->get();
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function firstWhere(string $key, mixed $value): ?Card
    {
        return array_first(
            array_filter($this->all(), fn (Card $card): bool => $value === $card->{$key}())
        );
    }
}
