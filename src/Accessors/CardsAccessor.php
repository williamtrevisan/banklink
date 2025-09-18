<?php

declare(strict_types=1);

namespace Banklink\Accessors;

use Banklink\Banks\Itau\Pipelines\CardsGetter;
use Banklink\Entities\Card;
use Illuminate\Support\Collection;

final class CardsAccessor
{
    /**
     * @return Collection<int, Card>
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function all(): Collection
    {
        return app()->make(CardsGetter::class)
            ->get();
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function firstWhere(string $key, mixed $value): ?Card
    {
        return $this->all()
            ->firstWhere(function (Card $card) use ($key, $value): bool {
                if (! method_exists($card, $key)) {
                    return false;
                }

                return $value === $card->{$key}();
            });
    }
}
